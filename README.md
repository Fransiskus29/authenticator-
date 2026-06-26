<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-3.x-FE3A08?style=flat-square&logo=livewire" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=flat-square&logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

<h1 align="center">🔐 SecureAuth — 2FA Authenticator</h1>

<p align="center">
  A web-based TOTP authenticator app built with Laravel. Manage your two-factor authentication codes across devices with end-to-end encryption, QR code scanning, and a sleek dark UI.
</p>

<p align="center">
  <a href="https://authenticator-production.up.railway.app">Live Demo</a>
</p>

---

## Features

- **TOTP Code Generation** — RFC 6238 compliant, generates 6-digit codes that refresh every 30 seconds
- **QR Code Scanner** — Scan QR codes from other authenticator apps to import accounts
- **Manual Entry** — Add accounts by entering account name and Base32 secret key
- **End-to-End Encrypted Backup** — Export/import your entire vault encrypted with Laravel's `Crypt` facade (AES-256-CBC)
- **Dark Mode** — System-aware theme with manual toggle, persisted across sessions
- **Gesture-Based UX** — Swipe left to archive (mobile), right-click context menu (desktop), long-press menu
- **Soft Delete with Recovery** — Archived accounts stay for 7 days before permanent deletion, fully restorable
- **Password Recovery** — Forgot password flow with email reset links
- **Responsive Design** — Mobile-first glassmorphism UI with Material Design icons

---

## Tech Stack

### Backend

| Technology | Version | Purpose |
|---|---|---|
| **PHP** | 8.4+ | Runtime |
| **Laravel** | 13.x | Framework, routing, auth, encryption |
| **Livewire** | 3.x | Reactive components (auth pages) |
| **Livewire Volt** | 1.x | Single-file Volt components for auth flows |
| **Google2FA** | 3.x | TOTP secret generation, QR code URLs, OTP computation |
| **PostgreSQL** | — | Production database (Supabase) |
| **SQLite** | — | Local development database |

### Frontend

| Technology | Purpose |
|---|---|
| **Tailwind CSS** | Utility-first styling, Material Design tokens |
| **Vite** | Asset bundling and HMR |
| **Blade Templates** | Server-side rendering with component layouts |
| **Material Symbols** | Google's icon set for UI elements |

### Infrastructure

| Service | Purpose |
|---|---|
| **Railway** | Production hosting and deployment |
| **Supabase** | Managed PostgreSQL database |
| **GitHub** | Source control and CI/CD triggers |

---

## How TOTP Works

This app implements the **Time-based One-Time Password (TOTP)** algorithm defined in [RFC 6238](https://datatracker.ietf.org/doc/html/rfc6238).

### The Flow

```
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│  Secret Key  │─────▶│  HMAC-SHA1  │─────▶│  Truncate   │
│  (Base32)    │      │  + Time     │      │  → 6 digits  │
└─────────────┘      └─────────────┘      └─────────────┘
       │                    │                    │
       │              Time = floor(             │
       │              unix_time / 30)           │
       │                                        ▼
   Stored in DB                          TOTP Code
   (encrypted)                         (refreshes every 30s)
```

1. **Secret Key Generation** — A 160-bit random secret is generated using `Google2FA::generateSecretKey()`, encoded in Base32 (RFC 4648)
2. **QR Code** — The secret is encoded into an `otpauth://` URI and rendered as a QR code for scanning by authenticator apps
3. **Code Computation** — Every 30 seconds, the current Unix timestamp is divided by 30 to get a time step. HMAC-SHA1(secret, time_step) is computed and truncated to 6 digits
4. **Validation** — When adding an account manually, the user must verify a code to prove they control the secret

### Validation Rules

- Secret keys must be valid Base32: characters `A-Z` and `2-7` only
- Decoded secret length must be between 10 and 64 bytes
- Invalid secrets are rejected at the controller level with a clear error message

---

## Encryption & Security

### Data at Rest

| Data | Protection |
|---|---|
| **User passwords** | bcrypt hashing (Laravel default) |
| **TOTP secrets** | Stored as plaintext Base32 in `two_factor_accounts.secret` |
| **Backup export** | AES-256-CBC encryption via `Crypt::encryptString()` |
| **Sessions** | Database-backed (production), file-backed (development) |

### Backup Encryption

When you export your authenticator vault, all account data (labels, secrets, issuers) is serialized to JSON and encrypted using Laravel's `Crypt` facade:

```php
// Export: encrypt
$encrypted = Crypt::encryptString($accounts->toJson());

// Import: decrypt
$json = Crypt::decryptString($request->input('backup_data'));
```

The encryption key is your app's `APP_KEY` (32-byte base64 string). Without this key, the backup file is unreadable.

### CSRF Protection

All forms include Laravel's CSRF token verification. The `X-CSRF-TOKEN` header is sent with AJAX requests, and `@csrf` directives protect traditional form submissions.

---

## Project Structure

```
authenticator/
├── app/
│   ├── Console/Commands/
│   │   └── PurgeDeletedAccounts.php    # Daily cron: deletes archived accounts > 7 days
│   ├── Http/Controllers/
│   │   ├── TwoFactorAccountController.php  # Core logic: CRUD, TOTP, backup
│   │   └── ProfileController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── TwoFactorAccount.php         # SoftDeletes, belongs to User
│   └── Providers/
│       └── AppServiceProvider.php        # HTTPS force in production
├── database/
│   └── migrations/
│       ├── create_two_factor_accounts_table.php
│       └── add_deleted_at_to_two_factor_accounts_table.php
├── resources/
│   ├── views/
│   │   ├── two-factor/
│   │   │   ├── index.blade.php          # Main dashboard with cards + timer
│   │   │   ├── create.blade.php         # QR scanner + manual entry form
│   │   │   ├── show.blade.php           # QR code display page
│   │   │   └── archived.blade.php       # Archived accounts with restore/force-delete
│   │   ├── components/layouts/
│   │   │   ├── app.blade.php            # Authenticated layout with sidebar
│   │   │   └── guest.blade.php          # Guest layout with dark theme init
│   │   └── livewire/pages/auth/         # Livewire Volt auth pages
│   └── css/app.css                       # Glassmorphism, animations, Material tokens
├── routes/
│   ├── web.php                           # All routes
│   └── console.php                       # Scheduler (daily purge)
└── bootstrap/app.php                     # Middleware config (TrustProxies)
```

---

## Routes

| Method | URI | Description |
|---|---|---|
| `GET` | `/` | Welcome page |
| `GET` | `/login` | Login form |
| `GET` | `/register` | Registration form |
| `GET` | `/dashboard` | User dashboard |
| `GET` | `/authenticator` | Account list with live TOTP codes |
| `GET` | `/authenticator/create` | Add new account (QR scan + manual) |
| `POST` | `/authenticator` | Store new account |
| `GET` | `/authenticator/{id}/code` | Fetch current TOTP code (AJAX) |
| `DELETE` | `/authenticator/{id}` | Archive account (soft delete) |
| `GET` | `/authenticator/archived` | View archived accounts |
| `POST` | `/authenticator/{id}/restore` | Restore archived account |
| `DELETE` | `/authenticator/{id}/force-delete` | Permanently delete account |
| `POST` | `/authenticator/export` | Export encrypted backup |
| `POST` | `/authenticator/import` | Import encrypted backup |

---

## Getting Started

### Prerequisites

- PHP 8.4+
- Composer
- Node.js 18+
- SQLite (dev) or PostgreSQL (production)

### Local Development

```bash
# Clone the repository
git clone https://github.com/Fransiskus29/authenticator.git
cd authenticator

# Install PHP dependencies
composer install

# Set up environment
cp .env.example .env
php artisan key:generate

# Install frontend dependencies
npm install

# Run migrations
php artisan migrate

# Build assets and start dev server
npm run dev
```

Visit `http://localhost:8000`.

### Production (Railway)

1. Connect your GitHub repo to Railway
2. Set environment variables:
   - `APP_KEY` — generate with `php artisan key:generate`
   - `APP_URL` — your Railway domain
   - `APP_ENV=production`
   - `DB_CONNECTION=pgsql` + Supabase credentials
3. Run migrations via the `/run-migrations` endpoint or `php artisan migrate`
4. The daily purge cron runs automatically via the scheduler

---

## Scheduled Tasks

| Task | Schedule | Description |
|---|---|---|
| `authenticator:purge` | Daily at midnight | Permanently deletes accounts archived > 7 days ago |

---

## Author

**Angga** — [GitHub](https://github.com/Fransiskus29)

---

## License

MIT License. See [LICENSE](LICENSE) for details.
