<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     title="Payment",
 *     description="Payment model",
 *     @OA\Property(
 *         property="id",
 *         description="Unique identifier for the payment",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="order_id",
 *         description="Order ID associated with the payment",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="payment_method",
 *         description="Payment method used",
 *         type="string",
 *         example="credit_card"
 *     ),
 *     @OA\Property(
 *         property="payment_status",
 *         description="Status of the payment",
 *         type="string",
 *         example="completed"
 *     ),
 *     @OA\Property(
 *         property="payment_amount",
 *         description="Amount of the payment",
 *         type="number",
 *         format="float",
 *         example="100.00"
 *     ),
 *     @OA\Property(
 *         property="buyer_id",
 *         description="ID of the buyer",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="seller_id",
 *         description="ID of the seller",
 *         type="integer",
 *         example="2"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Date and time when the payment was created",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 06:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Date and time when the payment was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 06:30:00"
 *     ),
 *     @OA\Property(
 *         property="order",
 *         description="Order associated with the payment",
 *         ref="#/components/schemas/Order"
 *     )
 * )
 * @method static create(array $all)
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
        'payment_amount',
        'buyer_id',
        'seller_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
