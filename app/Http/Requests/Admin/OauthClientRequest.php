<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\OauthClientRepositoryInterface;

class OauthClientRequest extends BaseRequest
{

    /** @var \App\Repositories\OauthClientRepositoryInterface */
    protected $oauthClientRepository;

    public function __construct(OauthClientRepositoryInterface $oauthClientRepository)
    {
        $this->oauthClientRepository = $oauthClientRepository;
    }

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
        return $this->oauthClientRepository->rules();
    }

    public function messages()
    {
        return $this->oauthClientRepository->messages();
    }

}
