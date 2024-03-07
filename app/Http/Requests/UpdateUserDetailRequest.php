<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDetailRequest extends FormRequest
{   
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
            'first_name' => 'string',
            'last_name' => 'string',
            'password' => 'string|min:8',
            'profile_picture' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
