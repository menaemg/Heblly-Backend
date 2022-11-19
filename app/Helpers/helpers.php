<?php

/**
 * Code for return Json Response API
 *
 * @return jsonResponse()
 */
if (!function_exists('jsonResponse')) {
    function jsonResponse($status, $message, $data = null, $code = null)
    {
        if (!function_exists('removeNull')) {
            function removeNull($value) {
                return !is_null($value);
            }
        }

        if($status) {
            return response()->json(array_filter([
                'status'  => true,
                'message'     => $message,
                'data'    => $data
            ], 'removeNull'), $code ?? 200);
        }

        return response()->json(array_filter([
            'status'  => false,
            'message'     => $message,
            'data' => $data
        ], 'removeNull'), $code ?? 400);
    }
}
