<?php

namespace App\Http\Controllers;


use http\Env\Response;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{

    /**
     * Build a body of json response
     *
     * @param integer $status
     * @param array $results
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function buildResponse($status, $results = [], $errors = [])
    {
        $response = [
            'errors' => $errors,
            'results' => $results
        ];

        return response()->json($response, $status);
    }

}
