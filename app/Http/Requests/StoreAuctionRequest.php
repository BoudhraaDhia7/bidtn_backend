<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuctionRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string', 
            'startingPrice' => 'required|integer|min:0',
            'startDate' => 'required|integer',
            'startingUserNumber' => 'required|integer|min:1',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.description' => 'required|string',
            'products.*.categories' => 'required|array',
            'products.*.categories.*' => 'required|numeric|exists:categories,id',
            'products.*.files' => 'required|array',
            'products.*.files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10000',
        ];
    }
    
}
