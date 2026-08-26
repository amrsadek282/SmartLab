<?php

namespace App\Models;

use App\Services\EgyptianPhoneNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'gender',
        'age',
        'national_id',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getNormalizedPhoneAttribute(): ?string
    {
        return EgyptianPhoneNormalizer::normalize($this->phone);
    }

    public function getFormattedPhoneAttribute(): string
    {
        return EgyptianPhoneNormalizer::formatDisplay($this->phone);
    }

    public function getOperatorNameAttribute(): ?string
    {
        return EgyptianPhoneNormalizer::getOperator($this->phone);
    }
}
