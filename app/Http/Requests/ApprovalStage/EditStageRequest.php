<?php

namespace App\Http\Requests\ApprovalStage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditStageRequest extends FormRequest
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
            'user_id' => [
                'required',
                'exists:users,id,deleted_at,NULL',
                Rule::unique('approval_stages', 'user_id')->ignore(request()->user_id, 'user_id'),
            ]
        ];
    }
}
