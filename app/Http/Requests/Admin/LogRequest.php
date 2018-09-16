<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\LogRepositoryInterface;

class LogRequest extends BaseRequest
{

    /** @var \App\Repositories\LogRepositoryInterface */
    protected $logRepository;

    public function __construct(LogRepositoryInterface $logRepository)
    {
        $this->logRepository = $logRepository;
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
        return $this->logRepository->rules();
    }

    public function messages()
    {
        return $this->logRepository->messages();
    }

}
