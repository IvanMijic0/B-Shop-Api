<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\TOTPSecret;
use App\Utils\Validator;
use App\Models\User;
use Carbon\Carbon;
use OTPHP\TOTP;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identifier", "password"}, 
     *             @OA\Property(property="identifier", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="time", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="time", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    function login(Request $request): JsonResponse
    {
        $identifier = $request->input('identifier');
        $password = $request->input('password');

        $identifierError = Validator::identifier($identifier);
        $passwordError = Validator::password($password);

        if ($identifierError || $passwordError) {
            return response()->json(['error' => $identifierError ?: $passwordError, 'time' => Carbon::now()], 422);
        }

        $user = User::where(function ($query) use ($identifier) {
            $query->where('username', $identifier)
                ->orWhere('email', $identifier);
        })->first();

        if (!$user || !password_verify($password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials', 'time' => Carbon::now()], 422);
        }

        if (!$token = auth()->login($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $expiresAtTimestamp = now()->addMinutes(auth()->factory()->getTTL() * 180)->timestamp;

        $user->personalAccessToken()->create([
            'user_id' => $user->id,
            'token' => $token,
            'last_used_at' => now(),
            'expires_at' => $expiresAtTimestamp
        ]);

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Authentication"},
     *     summary="User registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fullName", "username", "password", "email", "phoneNumber"},
     *             @OA\Property(property="fullName", type="string"),
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phoneNumber", type="string", format="phone")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="time", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="fullName", type="array",
     *                     @OA\Items(type="string", example="Full name must be at least 3 characters long.")
     *                 ),
     *                 @OA\Property(property="phoneNumber", type="array",
     *                     @OA\Items(type="string", example="Invalid phone number.")
     *                 ),
     *                 @OA\Property(property="username", type="array",
     *                     @OA\Items(type="string", example="Username must be at least 3 characters long.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="Invalid email address.")
     *                 ),
     *                 @OA\Property(property="password", type="array",
     *                     @OA\Items(type="string", example="Password must be at least 8 characters long.")
     *                 )
     *             ),
     *             @OA\Property(property="time", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    function register(Request $request): JsonResponse
    {
        $fullName = $request->input('fullName');
        $email = $request->input('email');
        $username = $request->input('username');
        $password = $request->input('password');
        $phoneNumber = $request->input('phoneNumber');

        $existingUser = User::where('email', $email)->orWhere('username', $username)->first();

        if ($existingUser) {
            $errors = [];
            if ($existingUser->email === $email) {
                $errors['email'] = 'Email already exists';
            }
            if ($existingUser->username === $username) {
                $errors['username'] = 'Username already exists';
            }
            return response()->json(['errors' => $errors, 'time' => Carbon::now()], 422);
        }

        $errors = Validator::validateRegister($fullName, $username, $password, $email, $phoneNumber);

        if (!empty($errors)) {
            return response()->json(['errors' => $errors, 'time' => Carbon::now()], 422);
        }

        $user = new User(
            [
                'full_name' => $fullName,
                'email' => $email,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone_number' => $phoneNumber
            ]
        );

        $user->save();

        return response()->json(['message' => 'Registration successful!', 'time' => Carbon::now()]);
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     tags={"Authentication"},
     *     summary="Get the authenticated User",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /* @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Refresh a token",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="expires_at", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @OA\Get(
     *     path="/auth/validateToken",
     *     tags={"Authentication"},
     *     summary="Validate user token",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token is valid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    function validateToken(): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json([
                'message' => 'Token is valid'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while validating the token'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/generateTOTPSecret",
     *     tags={"Authentication"},
     *     summary="Generate TOTP Secret",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="TOTP Secret generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="secret", type="string", description="Generated TOTP secret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="User already has a TOTP secret"
     *     )
     * )
     */
    function generateTOTPSecret(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($user->totpSecret()->exists()) {
            return response()->json(['error' => 'User already has a TOTP secret'], 422);
        }

        $totp = TOTP::create();

        $totpSecret = $user->totpSecret()->create([
            'secret' => $totp->getSecret()
        ]);

        return response()->json([
            'secret' => $totpSecret->secret
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/generateTOTPQRCode",
     *     tags={"Authentication"},
     *     summary="Generate TOTP QR Code",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="TOTP QR Code generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="qr_code_uri", type="string", description="URI of the generated QR code"),
     *             @OA\Property(property="qr_code", type="string", description="HTML image tag with the QR code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TOTP secret not found for the user"
     *     )
     * )
     */
    function generateTOTPQRCode(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $totpSecret = TOTPSecret::where('user_id', $user->id)->first();

        if (!$totpSecret) {
            return response()->json(['error' => 'TOTP secret not found for the user'], 404);
        }

        $otp = TOTP::createFromSecret($totpSecret->secret);
        $otp->setLabel("{$user->username}@SSSD_APP");

        $qrCodeUri = $otp->getQrCodeUri(
            'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
            '[DATA]'
        );

        return response()->json([
            'qr_code_uri' => $qrCodeUri,
            'qr_code' => "<img src='{$qrCodeUri}'>"
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/validateTOTPSecret",
     *     tags={"Authentication"},
     *     summary="Validate TOTP Secret",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"secret"},
     *             @OA\Property(property="secret", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TOTP secret is valid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TOTP secret not found for the user"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid TOTP secret"
     *     )
     * )
     */
    function validateTOTPSecret(Request $request): JsonResponse
    {
        $secret
            =        $totpSecret = str_replace(
                ' ',
                '',
                $request->input('secret')
            );

        $validationError = Validator::validateTOTPSecret($secret);

        if ($validationError) {
            return response()->json(['error' => $validationError], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $totpSecret = TOTPSecret::where('user_id', $user->id)->first();

        if (!$totpSecret) {
            return response()->json(['error' => 'TOTP secret not found for the user'], 404);
        }

        $otp = TOTP::createFromSecret($totpSecret->secret);

        if ($otp->verify($secret)) {
            return response()->json(['message' => 'TOTP secret is valid'], 200);
        } else {
            return response()->json(['error' => 'Invalid TOTP secret'], 422);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'jwt_access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 180
        ]);
    }
}
