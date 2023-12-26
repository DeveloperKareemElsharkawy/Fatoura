<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getNextAutoIncrementId')) {
    function getNextAutoIncrementId($tableName)
    {
        $result = DB::select("SHOW TABLE STATUS LIKE '{$tableName}'");

        return $result[0]->Auto_increment;
    }
}

if (!function_exists('generateToken')) {
    function generateToken($user)
    {
        return $user->createToken(config('app.name'))->plainTextToken;
    }
}

function generateResetCode(int $length = 4, bool $unique = true): ?string
{
    $min = pow(10, $length - 1); // Minimum value for the specified length
    $max = pow(10, $length) - 1; // Maximum value for the specified length

    if ($unique) {
        // Generate a unique code within the specified range
        $code = mt_rand($min, $max);
        // You might want to implement a check here to ensure the code is unique in your context
        return str_pad($code, $length, '0', STR_PAD_LEFT); // Pad the code to ensure it's of the specified length
    } else {
        return strval(mt_rand($min, $max)); // Generate a non-unique code within the specified range
    }
}

