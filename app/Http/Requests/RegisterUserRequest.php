<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'profile_picture' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
