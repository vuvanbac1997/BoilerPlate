<?php
namespace App\Http\Requests\API\V1;

class ArticleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = ($this->method() == 'PUT') ? $this->route('article') : 0;

        $rules = [
            'slug'               => 'required|string|unique:articles,slug,'.$id,
            'title'              => 'required|string',
            'keywords'           => 'string',
            'description'        => 'string',
            'content'            => 'string|required',
            'locale'             => 'string',
            'publish_started_at' => 'date_format:Y-m-d H:i:s|required',
            'publish_ended_at'   => 'date_format:Y-m-d H:i:s',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
