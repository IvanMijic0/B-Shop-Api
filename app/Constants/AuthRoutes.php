<?php

namespace App\Constants;

class AuthRoutes
{
    const LOGIN = 'login';
    const REGISTER = 'register';
    const VALIDATE_TOKEN = 'validateToken';
    const GENERATE_TOTP_SECRET = 'generateTOTPSecret';
    const GENERATE_TOTP_QR_CODE = 'generateTOTPQRCode';
    const VALIDATE_TOTP_SECRET = 'validateTOTPSecret';
    const LOGOUT = 'logout';
    const ME = 'me';
}
