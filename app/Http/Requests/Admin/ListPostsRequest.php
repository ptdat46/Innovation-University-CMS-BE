<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ListPostsRequest extends FormRequest
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
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'status' => 'sometimes|string|in:all,pending,posted',
            'category' => 'sometimes|string|in:all,news,events,clubs,student-life',
            'writer_id' => 'sometimes|string',
            'date_from' => 'sometimes|date_format:Y-m-d|nullable',
            'date_to' => 'sometimes|date_format:Y-m-d|nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'page.integer' => 'Trang phải là số nguyên.',
            'page.min' => 'Trang phải lớn hơn hoặc bằng 1.',
            'per_page.integer' => 'Số bài viết trên trang phải là số nguyên.',
            'per_page.min' => 'Số bài viết trên trang phải lớn hơn hoặc bằng 1.',
            'per_page.max' => 'Số bài viết trên trang không được vượt quá 100.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'category.in' => 'Danh mục không hợp lệ.',
            'date_from.date_format' => 'Ngày từ phải có định dạng YYYY-MM-DD.',
            'date_to.date_format' => 'Ngày đến phải có định dạng YYYY-MM-DD.',
        ];
    }
}
