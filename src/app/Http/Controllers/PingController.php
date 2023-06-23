<?php

namespace App\Http\Controllers;

class PingController extends Controller 
{
     /**
     * @OA\Get(
     *    path="/ping",
     *    operationId="ping",
     *    tags={"Ping"},
     *    summary="Get api health",
     *    description="Get api health",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="ping",type="datetime", example="2023-06-23T19:58:46.094723Z")
     *          )
     *       )
     *  )
     */
    function ping() {
        return response()->json(array('ping'=> now()));
    }

}