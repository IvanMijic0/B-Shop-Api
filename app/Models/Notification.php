<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="Notification",
 *     description="Notification model",
 *     @OA\Property(
 *         property="id",
 *         description="Unique identifier for the notification",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         description="User ID associated with the notification",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         description="Content of the notification",
 *         type="string",
 *         example="You have a new message."
 *     ),
 *     @OA\Property(
 *         property="notification_type",
 *         description="Type of the notification",
 *         type="string",
 *         example="email"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Date and time when the notification was created",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 08:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Date and time when the notification was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 08:30:00"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         description="User associated with the notification",
 *         ref="#/components/schemas/User"
 *     )
 * )
 * @method static create(array $all)
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'notification_type',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
