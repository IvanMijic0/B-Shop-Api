<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="TOTPSecret",
 *     description="TOTP Secret model",
 *     @OA\Property(
 *         property="id",
 *         description="Unique identifier for the TOTP secret",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         description="User ID associated with the TOTP secret",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="secret",
 *         description="The TOTP secret key",
 *         type="string",
 *         example="JBSWY3DPEHPK3PXP"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Date and time when the TOTP secret was created",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 01:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Date and time when the TOTP secret was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 01:30:00"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         description="User associated with the TOTP secret",
 *         ref="#/components/schemas/User"
 *     )
 * )
 */
class TOTPSecret extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'secret'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
