<?php
namespace App\Http\Requests\API\V1;

use App\Http\Requests\BaseRequest;
use App\Http\Responses\API\V1\Response;

class Request extends BaseRequest
{
    /**
     * Get the failed validation response for the request.
     *
     * @params array $errors
     */
    public function response(array $errors)
    {
        $transformed = [];

        foreach ($errors as $field => $message) {
            $transformed[$field] = $message[0];
        }

        return Response::response(40001, $transformed);
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
