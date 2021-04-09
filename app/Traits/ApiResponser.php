<?php

namespace App\Traits;

trait ApiResponser{
    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message = null, $code)
    {
        if ($message){
            return response()->json([
                'status'=>'Error',
                'message' => $message,
                'data' => null
            ], $code);
        }else{
            return response()->json(null, $code);
        }

    }
}
