<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UsernameGenerator
{
    public static function generate($role, $batchYear = null)
    {
        $rolePrefixes = [
            'admin' => 'ADM',
            'registrar' => 'REG',
            'student' => 'STD',
            'undergraduate' => 'UGR',
            'teacher' => 'TCH',
            'coordinator' => 'COR',
        ];

        // Prefix based on role
        $prefix = $rolePrefixes[$role] ?? 'USR';

        // Determine the year
        $year = $batchYear ? $batchYear : now()->year;

        // Generate the unique number part
        $uniqueId = "";
        if ($batchYear) {
            $uniqueId = self::getNextId($prefix, $batchYear);
        } else {
            $uniqueId = self::getNextId($prefix);
        }

        // Combine to create the username
        return "{$prefix}/{$uniqueId}/{$year}";
    }

    private static function getNextId($prefix, $year = null)
    {
        // Find the last user with the same prefix
        $lastUser = '';
        if ($year) {
            $lastUser = DB::table('users')
                ->where('username', 'LIKE', "{$prefix}/%/{$year}")
                ->latest('id')
                ->value('username');
        } else {
            $lastUser = DB::table('users')
                ->where('username', 'LIKE', "{$prefix}/%")
                ->latest('id')
                ->value('username');
        }

        if ($lastUser) {
            // Extract the numeric part (e.g., ADM/1000/2024 -> 1000)
            preg_match('/\/(\d+)\//', $lastUser, $matches);
            $lastId = $matches[1] ?? 1000;

            return $lastId + 1;
        }

        // Start at 1000 if no previous users with the same prefix
        return 1000;
    }
}
