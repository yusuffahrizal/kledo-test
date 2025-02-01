<?php

namespace App\Http\Requests\Approvers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditApproverRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore(request()->route('approver'))
            ],
        ];
    }
}
