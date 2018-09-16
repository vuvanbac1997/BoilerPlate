<?php
namespace App\Http\Responses\API\V1;

class Response
{
    public static function response($code = 200, $data = null)
    {
        $config              = config("api.$code");
        $response['code']    = $config['httpStatusCode'];
        $response['message'] = $config['message'];
        $response['data']    = $data;

        return response()->json($response)->setStatusCode($config['httpStatusCode']);
    }
}

?>