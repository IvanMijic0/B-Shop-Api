<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Operations related to orders"
 * )
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *      path="/orders",
     *      tags={"Orders"},
     *      summary="Get list of orders",
     *      description="Returns list of orders",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Order")
     *          )
     *      )
     * ),
     *  security={{"bearerAuth": {}}}
     */
    public function index(): JsonResponse
    {
        return response()->json(Order::all());
    }

    /**
     * @OA\Post(
     *      path="/orders",
     *      tags={"Orders"},
     *      summary="Create a new order",
     *      description="Create a new order",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      )
     * ),
     *  security={{"bearerAuth": {}}}
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(Order::create($request->all()), 201);
    }

    /**
     * @OA\Get(
     *      path="/orders/{id}",
     *      tags={"Orders"},
     *      summary="Get specified order",
     *      description="Returns specified order",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the order",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      )
     * ),
     *  security={{"bearerAuth": {}}}
     */
    public function show(Order $order): JsonResponse
    {
        return response()->json($order);
    }

    /**
     * @OA\Put(
     *      path="/orders/{id}",
     *      tags={"Orders"},
     *      summary="Update specified order",
     *      description="Update specified order",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the order",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      )
     * ),
     *  security={{"bearerAuth": {}}}
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        return response()->json($order->update($request->all()));
    }

    /**
     * @OA\Delete(
     *      path="/orders/{id}",
     *      tags={"Orders"},
     *      summary="Delete specified order",
     *      description="Delete specified order",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the order",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      )
     * ),
     *  security={{"bearerAuth": {}}}
     */
    public function destroy(Order $order): JsonResponse
    {
        return response()->json($order->delete());
    }
}
