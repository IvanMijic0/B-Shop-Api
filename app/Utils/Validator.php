<?php

namespace App\Utils;

use App\Http\Services\HaveIBeenPwnedService;
use Exception;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;
use Illuminate\Support\Facades\Cache;

class Validator
{
    private const RESERVED_NAMES = ['admin', 'root', 'superuser'];


    /**
     * @throws Exception
     */
    public static function validateRegister(string $fullName, string $username, string $password, string $email, string $phoneNumber): ?array
    {
        $fullNameError = self::validateFullName($fullName);
        $usernameError = self::username($username);
        $passwordError = self::password($password);
        $emailError = self::email($email);
        $phoneNumberError = self::validatePhoneNumber($phoneNumber);

        $errors = [];

        if ($fullNameError) {
            $errors['fullName'] = $fullNameError;
        }

        if ($usernameError) {
            $errors['username'] = $usernameError;
        }

        if ($passwordError) {
            $errors['password'] = $passwordError;
        }

        if ($emailError) {
            $errors['email'] = $emailError;
        }

        if ($phoneNumberError) {
            $errors['phoneNumber'] = 'Invalid phone number.';
        }

        return count($errors) > 0 ? $errors : null;
    }

    public static function validateTOTPSecret(string $totpSecret): ?string
    {
        if (!self::max($totpSecret, 6)) {
            return 'TOTP secret must be at least 6 characters long.';
        }

        if (!self::max($totpSecret, 16)) {
            return 'TOTP secret must be at most 16 characters long.';
        }

        if (!self::isAlphaNumeric($totpSecret)) {
            return 'TOTP secret must contain only alphanumeric characters.';
        }

        return null;
    }


    public static function identifier(string $identifier): ?string
    {
        if (self::email($identifier)) {
            return null;
        }

        if (self::username($identifier)) {
            return null;
        }

        return 'Invalid identifier. It must be either a valid email or a valid username.';
    }

    public static function validateFullName(string $fullName): ?string
    {
        if (!self::min($fullName, 3)) {
            return 'Full name must be at least 3 characters long.';
        }

        if (!self::max($fullName, 100)) {
            return 'Full name must be at most 100 characters long.';
        }

        if (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
            return 'Full name must contain only letters and spaces.';
        }

        return null;
    }

    public static function username(string $username): ?string
    {
        if (!self::min($username, 3)) {
            return 'Username must be at least 3 characters long.';
        }

        if (!self::isAlphaNumeric($username)) {
            return 'Username must contain only alphanumeric characters.';
        }

        if (!self::isNotReserved($username)) {
            return 'Username is reserved.';
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public static function password(string $password): ?string
    {
        if (!self::min($password, 8)) {
            return 'Password must be at least 8 characters long.';
        }

        if (!self::max($password, 30)) {
            return 'Password must be at most 30 characters long.';
        }

        if (!self::validatePasswordStructure($password)) {
            return 'Password must contain at least one lowercase letter, one uppercase letter, one digit, and no spaces.';
        }

        if (!self::validateHaveIBeenPwned($password)) {
            return 'Password has been compromised.';
        }

        return null;
    }

    public static function email(string $email): ?string
    {
        if (!self::validateEmailStructure($email)) {
            return 'Invalid email address.';
        }

        if (!self::validateTLD($email)) {
            return 'Invalid top-level domain.';
        }

        if (!self::validateMXRecord($email)) {
            return 'Invalid domain.';
        }

        return null;
    }

    public static function min(string $value, int $minValue): bool
    {
        return mb_strlen($value) >= $minValue;
    }

    public static function max(string $value, int $maxValue): bool
    {
        return mb_strlen($value) <= $maxValue;
    }

    public static function isAlphaNumeric(string $value): bool
    {
        return ctype_alnum($value);
    }

    public static function isNotReserved(string $value): bool
    {
        return !in_array(strtolower($value), self::RESERVED_NAMES);
    }

    public static function validateEmailStructure(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateTLD(string $email): bool
    {
        $segments = explode('.', $email);
        $tld = mb_strtoupper(end($segments));
        return in_array($tld, Cache::get('tlds'));
    }

    public static function validateMXRecord(string $email): bool
    {
        $emailParts = explode('@', $email);
        $domain = $emailParts[1];

        $mx_details = [];
        if (getmxrr($domain, $mx_details)) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePhoneNumber(string $phoneNumber): bool
    {
        $phone_util = PhoneNumberUtil::getInstance();

        try {
            $phone_number = $phone_util->parse($phoneNumber);
            return $phone_util->getNumberType($phone_number) === PhoneNumberType::MOBILE;
        } catch (NumberParseException) {
            return false;
        }
    }

    public static function validatePasswordStructure(string $password): bool
    {
        return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}$/", $password);
    }

    /**
     * @throws Exception
     */
    public static function validateHaveIBeenPwned(string $password): bool
    {
        $pwned = new HaveIBeenPwnedService();

        return $pwned->checkPassword($password);
    }
}
