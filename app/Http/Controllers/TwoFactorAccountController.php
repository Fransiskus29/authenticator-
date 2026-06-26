<?php

namespace App\Http\Controllers;

use App\Models\TwoFactorAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAccountController extends Controller
{
    private Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function index(Request $request)
    {
        $accounts = auth()->user()->twoFactorAccounts;

        if ($search = $request->input('q')) {
            $accounts = $accounts->filter(function ($account) use ($search) {
                return str_contains(strtolower($account->label), strtolower($search))
                    || str_contains(strtolower($account->issuer ?? ''), strtolower($search));
            });
        }

        $codes = $accounts->mapWithKeys(function ($account) {
            return [$account->id => $this->getCurrentCode($account->secret)];
        });

        return view('two-factor.index', compact('accounts', 'codes'));
    }

    public function archived()
    {
        $accounts = auth()->user()->twoFactorAccounts()->onlyTrashed()->get();

        return view('two-factor.archived', compact('accounts'));
    }

    public function create()
    {
        return view('two-factor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'secret' => 'nullable|string|max:255',
        ]);

        $secret = $request->input('secret');

        if (!empty($secret)) {
            $secret = strtoupper(trim($secret));
            $secret = preg_replace('/\s+/', '', $secret);

            // Validate Base32 (RFC 4648): A-Z, 2-7, =
            if (!preg_match('/^[A-Z2-7]+=*$/', $secret)) {
                return back()->withErrors(['secret' => 'Invalid secret key. Must be a valid Base32 string (A-Z, 2-7).']);
            }

            // Validate key length (16-64 bytes is standard for TOTP)
            $decoded = $this->base32Decode($secret);
            if (strlen($decoded) < 10 || strlen($decoded) > 64) {
                return back()->withErrors(['secret' => 'Secret key length is invalid.']);
            }
        } else {
            $secret = $this->google2fa->generateSecretKey();
        }

        $account = auth()->user()->twoFactorAccounts()->create([
            'label' => $request->label,
            'secret' => $secret,
            'issuer' => $request->issuer,
        ]);

        if ($request->input('secret')) {
            return redirect()->route('two-factor.index')
                ->with('success', 'Account "' . $request->label . '" added successfully!');
        }

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            $request->issuer ?? config('app.name', 'Authenticator'),
            $request->label,
            $secret
        );

        return view('two-factor.show', compact('account', 'qrCodeUrl'));
    }

    public function getCode(Request $request, TwoFactorAccount $account): JsonResponse
    {
        if ($account->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $code = $this->getCurrentCode($account->secret);
        $timestamp = time();
        $remaining = 30 - ($timestamp % 30);

        return response()->json([
            'code' => $code,
            'remaining' => $remaining,
            'formatted' => substr($code, 0, 3) . ' ' . substr($code, 3),
        ]);
    }

    public function destroy(TwoFactorAccount $account)
    {
        if ($account->user_id !== auth()->id()) {
            abort(403);
        }

        $account->delete(); // Soft delete via SoftDeletes trait

        return redirect()->route('two-factor.index')
            ->with('success', '"' . $account->label . '" archived. It will be permanently deleted after 7 days.');
    }

    public function restore(TwoFactorAccount $account)
    {
        if ($account->user_id !== auth()->id()) {
            abort(403);
        }

        $account->restore();

        return redirect()->route('two-factor.archived')
            ->with('success', '"' . $account->label . '" has been restored.');
    }

    public function forceDelete(TwoFactorAccount $account)
    {
        if ($account->user_id !== auth()->id()) {
            abort(403);
        }

        $account->forceDelete();

        return redirect()->route('two-factor.archived')
            ->with('success', '"' . $account->label . '" has been permanently deleted.');
    }

    public function export()
    {
        $accounts = auth()->user()->twoFactorAccounts->map(function ($account) {
            return [
                'label' => $account->label,
                'issuer' => $account->issuer,
                'secret' => $account->secret,
            ];
        });

        $encrypted = Crypt::encryptString($accounts->toJson());

        return response()->json([
            'data' => $encrypted,
            'filename' => 'authenticator-backup-' . now()->format('Y-m-d') . '.enc',
            'count' => $accounts->count(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'backup_data' => 'required|string',
        ]);

        try {
            $json = Crypt::decryptString($request->input('backup_data'));
            $accounts = json_decode($json, true);

            if (!is_array($accounts)) {
                return back()->withErrors(['backup_data' => 'Invalid backup data.']);
            }

            $imported = 0;
            foreach ($accounts as $account) {
                if (empty($account['label']) || empty($account['secret'])) continue;

                $secret = strtoupper(trim($account['secret']));
                $secret = preg_replace('/\s+/', '', $secret);

                // Skip invalid Base32 secrets
                if (!preg_match('/^[A-Z2-7]+=*$/', $secret)) continue;

                auth()->user()->twoFactorAccounts()->create([
                    'label' => $account['label'],
                    'issuer' => $account['issuer'] ?? null,
                    'secret' => $secret,
                ]);
                $imported++;
            }

            return redirect()->route('two-factor.index')
                ->with('success', "Successfully imported {$imported} account(s)!");
        } catch (\Exception $e) {
            return back()->withErrors(['backup_data' => 'Failed to decrypt backup. The data may be corrupted or from a different app key.']);
        }
    }

    private function getCurrentCode(string $secret): string
    {
        try {
            return $this->google2fa->getCurrentOtp($secret);
        } catch (\Exception $e) {
            return '------';
        }
    }

    private function base32Decode(string $input): string
    {
        $map = [
            'A' => 0,  'B' => 1,  'C' => 2,  'D' => 3,  'E' => 4,  'F' => 5,
            'G' => 6,  'H' => 7,  'I' => 8,  'J' => 9,  'K' => 10, 'L' => 11,
            'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17,
            'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
            'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27, '4' => 28, '5' => 29,
            '6' => 30, '7' => 31,
        ];
        $input = rtrim($input, '=');
        $buffer = 0;
        $bitsLeft = 0;
        $output = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $val = $map[$input[$i]] ?? -1;
            if ($val < 0) return '';
            $buffer = ($buffer << 5) | $val;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $output .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }
        return $output;
    }
}
