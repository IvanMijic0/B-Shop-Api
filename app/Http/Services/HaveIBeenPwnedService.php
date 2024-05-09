<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Utils\CurlUtil;

class HaveIBeenPwnedService
{
    private $curl;

    public function __construct()
    {
        $this->curl = CurlUtil::getInstance();
    }

    public function checkPassword(string $password): bool
    {
        $sha1Password = strtoupper(sha1($password));

        $prefix = substr($sha1Password, 0, 5);
        $suffix = substr($sha1Password, 5);

        $response = $this->curl->get("https://api.pwnedpasswords.com/range/" . $prefix);

        if ($response === false) {
            Log::error('Could not retrieve data from the API.');
            return false;
        }

        if (!str_contains($response, $suffix)) {
            return true;
        }

        return false;
    }
}
