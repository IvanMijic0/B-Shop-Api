<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(
 *         property="id",
 *         description="Unique identifier for the product",
 *         type="integer",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         description="Name of the product",
 *         type="string",
 *         example="Smartphone"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         description="Description of the product",
 *         type="string",
 *         example="A high-end smartphone with advanced features."
 *     ),
 *     @OA\Property(
 *         property="price",
 *         description="Price of the product",
 *         type="number",
 *         format="float",
 *         example="599.99"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Date and time when the product was created",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 03:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Date and time when the product was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2024-05-12 03:30:00"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         description="Categories associated with the product",
 *         type="array",
 *         @OA\Items(
 *             ref="#/components/schemas/Category"
 *         )
 *     ),
 *     @OA\Property(
 *         property="reviews",
 *         description="Reviews associated with the product",
 *         type="array",
 *         @OA\Items(
 *             ref="#/components/schemas/Review"
 *         )
 *     ),
 *     @OA\Property(
 *         property="carts",
 *         description="Carts associated with the product",
 *         type="array",
 *         @OA\Items(
 *             ref="#/components/schemas/Cart"
 *         )
 *     )
 * )
 * @method static pluck(string $string)
 * @method static create(array $all)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'seller_id',
        'category_id',
        'image_url'
    ];

    public function category(): HasMany
    {
        return $this->hasMany(Category::class);
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
