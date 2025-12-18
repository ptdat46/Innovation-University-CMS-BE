<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'excerpt' => 'nullable|string|max:255',
            'status' => 'required|in:pending,posted',
            'post_day' => 'nullable|date_format:Y-m-d H:i:s',
            'writer_id' => 'required|exists:users,id',
            'featured_image' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'pdf_file' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề bài viết là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung bài viết là bắt buộc.',
            'content.array' => 'Nội dung phải ở định dạng JSON hợp lệ.',
            'excerpt.max' => 'Tóm tắt không được vượt quá 500 ký tự.',
            'status.required' => 'Trạng thái bài viết là bắt buộc.',
            'status.in' => 'Trạng thái phải là: pending, posted',
            'post_day.date_format' => 'Thời gian lên lịch phải ở định dạng: Y-m-d H:i:s.',
            'writer_id.required' => 'ID tác giả là bắt buộc.',
            'writer_id.exists' => 'ID tác giả không tồn tại trong hệ thống.',
            'featured_image.max' => 'Đường dẫn hình ảnh không được vượt quá 255 ký tự.',
            'category.max' => 'Danh mục không được vượt quá 100 ký tự.',
        ];
    }
}