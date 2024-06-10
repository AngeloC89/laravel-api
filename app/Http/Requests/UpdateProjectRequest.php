<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
            'title' =>  [
                'required',
                'min:5',
                'max:200',
                Rule::unique('projects')->ignore($this->project->id),
            ],
            'content' => 'required',
            'image' => 'image'
        ];
    }

    public function messages()
    {
        return [
        'title.required' => 'Inserisci il titolo.',
        'titolo.min' => 'Inserisci 5 caratteri minimo.',
        'titolo.max'=> 'Il titolo non può avere più di 200 caratteri.',
        'content.required' => 'Inserisci un contenuto',
        'image.required' => 'Inserisci un\'immagine',

        ];
    }
}
