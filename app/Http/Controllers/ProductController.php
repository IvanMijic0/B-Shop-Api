<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Operations related to products"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/products",
     *      tags={"Products"},
     *      summary="Get list of products",
     *      description="Returns list of products",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Product")
     *          )
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Product::all());
    }

    /**
     * @OA\Post(
     *      path="/products",
     *      tags={"Products"},
     *      summary="Create a new product",
     *      description="Create a new product",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(Product::create($request->all()), 201);
    }

    /**
     * @OA\Get(
     *      path="/products/{id}",
     *      tags={"Products"},
     *      summary="Get specified product",
     *      description="Returns specified product",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the product",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * @OA\Put(
     *      path="/products/{id}",
     *      tags={"Products"},
     *      summary="Update specified product",
     *      description="Update specified product",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the product",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        return response()->json($product->update($request->all()));
    }

    /**
     * @OA\Delete(
     *      path="/products/{id}",
     *      tags={"Products"},
     *      summary="Delete specified product",
     *      description="Delete specified product",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the product",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        return response()->json($product->delete());
    }
}
