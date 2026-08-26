<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $targetUser */
        $targetUser = $this->route('user');
        $targetUserId = $targetUser instanceof User ? $targetUser->id : $targetUser;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($targetUserId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'receptionist', 'technician'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }

    /**
     * Configure additional validator checks for last admin protection.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            /** @var User $targetUser */
            $targetUser = $this->route('user');
            if (! ($targetUser instanceof User)) {
                $targetUser = User::find($targetUser);
            }

            if ($targetUser && $targetUser->isAdmin()) {
                $adminCount = User::where('role', 'admin')->where('is_active', true)->count();

                // If this is the only active admin
                if ($adminCount <= 1 && $targetUser->isActive()) {
                    if ($this->input('role') !== 'admin') {
                        $v->errors()->add('role', 'Cannot change the role of the only active Administrator in the system.');
                    }
                    if (! $this->boolean('is_active')) {
                        $v->errors()->add('is_active', 'Cannot deactivate the only active Administrator in the system.');
                    }
                }
            }
        });
    }
}
