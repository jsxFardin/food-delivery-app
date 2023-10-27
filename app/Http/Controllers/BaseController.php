<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccess($data = null, $message = 'Data found', $status = 200)
    {
        return response()->json(['message' => $message, 'data' => $data], $status);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message, $code = 404, $errors = null)
    {
        return response()->json(['message' => $message, 'errors' => $errors], $code);
    }
}
