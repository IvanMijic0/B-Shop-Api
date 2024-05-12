<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Carts",
 *     description="Operations about carts"
 * )
 */
class CartController extends Controller
{
    /**
     * @OA\Get(
     *      path="/carts",
     *      tags={"Carts"},
     *      summary="Get list of carts",
     *      description="Returns list of carts",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Cart")
     *          )
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Cart::all());
    }

    /**
     * @OA\Post(
     *      path="/carts",
     *      tags={"Carts"},
     *      summary="Create a new cart",
     *      description="Create a new cart",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Cart")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Cart")
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(Cart::create($request->all()), 201);
    }

    /**
     * @OA\Get(
     *      path="/carts/{id}",
     *      tags={"Carts"},
     *      summary="Get specified cart",
     *      description="Returns specified cart",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the cart",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Cart")
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */
    public function show(Cart $cart): JsonResponse
    {
        return response()->json($cart);
    }

    /**
     * @OA\Put(
     *      path="/carts/{id}",
     *      tags={"Carts"},
     *      summary="Update specified cart",
     *      description="Update specified cart",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the cart",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Cart")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Cart")
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, Cart $cart): JsonResponse
    {
        return response()->json($cart->update($request->all()));
    }

    /**
     * @OA\Delete(
     *      path="/carts/{id}",
     *      tags={"Carts"},
     *      summary="Delete specified cart",
     *      description="Delete specified cart",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the cart",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(Cart $cart): JsonResponse
    {
        return response()->json($cart->delete());
    }
}
