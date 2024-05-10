<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property mixed $id
 * @property mixed $full_name
 * @property mixed $username
 * @property mixed $email
 * @property mixed $phone_number
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier for the user",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         type="string",
 *         description="The full name of the user",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="username",
 *         type="string",
 *         description="The username of the user",
 *         example="johndoe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="The email address of the user",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="The password of the user (hashed)",
 *         example="$2y$10$N0wbN10w.0.yBhEWwH2fveCAeAxFxuIQJ4O7QHBTQm7v9eN6LpfSi"
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         description="The phone number of the user",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp of email verification",
 *         example="2022-03-28T10:15:00Z"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp of when the user was created",
 *         example="2022-03-28T10:15:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp of when the user was last updated",
 *         example="2022-03-28T10:15:00Z"
 *     )
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'phone_number',
        'image_url',
        'personal_details',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'full_name' => $this->full_name,
                'username' => $this->username,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
            ]
        ];
    }

    /**
     * Get the TOTP secret record associated with the user.
     */
    public function totpSecret(): HasOne
    {
        return $this->hasOne(TOTPSecret::class);
    }

    /**
     * Get the Personal Access Token record associated with the user.
     */
    public function personalAccessToken(): HasMany
    {
        return $this->hasMany(PersonalAccessToken::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
