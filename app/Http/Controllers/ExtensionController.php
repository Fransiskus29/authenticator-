<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class ExtensionController extends Controller
{
    public function download()
    {
        $extensionPath = base_path('extension');
        $zipName = 'secureauth-extension.zip';
        $zipPath = storage_path('app/' . $zipName);

        // Delete old zip if exists
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors(['error' => 'Failed to create zip']);
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extensionPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $relativePath = substr($file->getPathname(), strlen($extensionPath) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }

        $zip->close();

        return Response::download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}