<?php

namespace App\Http\Requests\ApprovalStage;

use Illuminate\Foundation\Http\FormRequest;

class CreateStageRequest extends FormRequest
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
                'unique:approval_stages,user_id',
            ]
        ];
    }
}
