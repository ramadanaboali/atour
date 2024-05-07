<?php

namespace App\Traits;
use Illuminate\Http\Response;

trait ApiResponser{

    public function successResponse($data, $code = Response::HTTP_OK){
        // $data = array('a', 'b');
        return response($data, $code)->header('Content-Type', 'application/json');
    }

    function apiResponse($success = false, $data = null, $message = '', $errors = null, $code = 200, $version = 1)
    {
        $response = [
            'status'  => $success,
            'code'  => $code,
            'data'    => $data,
            'message' => $message,
            'errors'  => $errors,
        ];
        return response()->json($response, $code);
    }

    public function errorResponse($message, $code){
        return response()->json(['message' => $message, 'code' => $code], $code);
    }

    public function errorMessage($message, $code){
        return response($message, $code)->header('Content-Type', 'application/json');
    }

}
