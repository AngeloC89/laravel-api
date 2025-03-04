<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
            'title' => [
                'required',
                'max:200',
                'min:5 ',

             
                
            ],
            'link' => 'nullable',
            'user_id' => 'nullable',
            'content' => 'required',
            'type_id' => 'nullable|exists:types,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Inserisci il titolo.',
            'titolo.min' => 'Inserisci 5 caratteri minimo.',
            'titolo.max' => 'Il titolo non può avere più di 200 caratteri.',
            'content.required' => 'Inserisci una descrizioine del progetto',
            'image.required' => 'Inserisci un\'immagine',

        ];
    }
}
