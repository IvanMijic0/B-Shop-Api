<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 *     title="Order",
 *     description="Order model",
 *     @OA\Property(
 *         property="id",
 *         description="Unique identifier for the order",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         description="User ID associated with the order",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="product_id",
 *         description="Product ID associated with the order",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         description="Quantity of the product in the order",
 *         type="integer",
 *         example="2"
 *     ),
 *     @OA\Property(
 *         property="order_status",
 *         description="Status of the order",
 *         type="string",
 *         example="pending"
 *     ),
 *     @OA\Property(
 *         property="tracking_status",
 *         description="Status of the order tracking",
 *         type="string",
 *         example="shipped"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Date and time when the order was created",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 07:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Date and time when the order was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 07:30:00"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         description="User associated with the order",
 *         ref="#/components/schemas/User"
 *     ),
 *     @OA\Property(
 *         property="payment",
 *         description="Payment associated with the order",
 *         ref="#/components/schemas/Payment"
 *     )
 * )
 * @method static create(array $all)
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'order_status',
        'tracking_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
