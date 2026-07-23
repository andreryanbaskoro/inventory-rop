<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ifsnop\Mysqldump\Mysqldump;
use Exception;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        return view('backup.index');
    }

    public function download()
    {
        try {
            $dbName = env('DB_DATABASE', 'inventory_rop');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPass = env('DB_PASSWORD', '');
            $dbHost = env('DB_HOST', '127.0.0.1');

            $date = now()->format('Y-m-d_H-i-s');
            $fileName = "backup_{$dbName}_{$date}.sql";
            $storagePath = storage_path("app/backups");

            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }

            $filePath = $storagePath . '/' . $fileName;

            // Pure PHP MySQL dump (no mysqldump.exe required)
            $dump = new Mysqldump("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
            $dump->start($filePath);

            return response()->download($filePath)->deleteFileAfterSend(true);
            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membuat backup database: ' . $e->getMessage());
        }
    }
}
