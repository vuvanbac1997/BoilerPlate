<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\TestRepositoryInterface;

class TestRequest extends BaseRequest
{

    /** @var \App\Repositories\TestRepositoryInterface */
    protected $testRepository;

    public function __construct(TestRepositoryInterface $testRepository)
    {
        $this->testRepository = $testRepository;
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
        return $this->testRepository->rules();
    }

    public function messages()
    {
        return $this->testRepository->messages();
    }

}
