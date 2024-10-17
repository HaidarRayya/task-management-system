<?php

namespace App\Http\Requests\Task;

use App\Rules\CheckTask;
use App\Rules\TaskPriority;
use App\Rules\TaskType;
use App\Services\TaskRequestService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    protected $taskRequestService;
    public function __construct(TaskRequestService $taskRequestService)
    {
        $this->taskRequestService = $taskRequestService;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:255', 'string'],
            'description' => ['required', 'min:25', 'max:255', 'string'],
            'priority' =>    ['required', new TaskPriority],
            'type' => ['required', new TaskType],
            'due_date' => ['required', 'date_format:Y-m-d H:i', 'after:now'],
            'depends_on' => ['sometimes', new CheckTask],
        ];
    }


    public function attributes(): array
    {
        return  $this->taskRequestService->attributes();
    }
    public function failedValidation(Validator $validator)
    {
        $this->taskRequestService->failedValidation($validator);
    }
    public function messages(): array
    {
        $messages = $this->taskRequestService->messages();
        $messages['required'] = 'حقل :attribute هو حقل اجباري ';
        return $messages;
    }
}