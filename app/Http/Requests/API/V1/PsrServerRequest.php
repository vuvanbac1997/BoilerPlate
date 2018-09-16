<?php
namespace App\Http\Requests\API\V1;

use App\Http\Requests\BaseRequest;
use Zend\Diactoros\ServerRequest;

class PsrServerRequest extends ServerRequest
{
    public static function createFromRequest(BaseRequest $request, $params = null)
    {
        if (is_null($params)) {
            $params = $request->all();
        }

        return new static(
            $request->server->all(), [], $request->fullUrl(), 
            $request->getMethod(), 'php://input', [],
            $request->cookies->all(), $request->query->all(), $params
        );
    }
}
