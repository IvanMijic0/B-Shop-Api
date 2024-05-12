<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Notifications",
 *     description="Operations related to notifications"
 * )
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *      path="/notifications",
     *      tags={"Notifications"},
     *      summary="Get list of notifications",
     *      description="Returns list of notifications",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Notification")
     *          )
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Notification::all());
    }

    /**
     * @OA\Post(
     *      path="/notifications",
     *      tags={"Notifications"},
     *      summary="Create a new notification",
     *      description="Create a new notification",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Notification")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Notification")
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(Notification::create($request->all()), 201);
    }

    /**
     * @OA\Get(
     *      path="/notifications/{id}",
     *      tags={"Notifications"},
     *      summary="Get specified notification",
     *      description="Returns specified notification",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the notification",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Notification")
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show(Notification $notification): JsonResponse
    {
        return response()->json($notification);
    }

    /**
     * @OA\Put(
     *      path="/notifications/{id}",
     *      tags={"Notifications"},
     *      summary="Update specified notification",
     *      description="Update specified notification",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the notification",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Notification")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Notification")
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, Notification $notification): JsonResponse
    {
        return response()->json($notification->update($request->all()));
    }

    /**
     * @OA\Delete(
     *      path="/notifications/{id}",
     *      tags={"Notifications"},
     *      summary="Delete specified notification",
     *      description="Delete specified notification",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the notification",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(Notification $notification): JsonResponse
    {
        return response()->json($notification->delete());
    }
}
