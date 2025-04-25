<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response($data, $status = 200, $messsage = 'Success Get Data')
    {
        $result['data'] = $data;
        if ($messsage) {
            $result['message'] = $messsage;
        }
        if (isset($result['message'])) {
            $status = 200;
        } else {
            $status = 500;
        }

        return response()->json($result, $status);
    }
}
