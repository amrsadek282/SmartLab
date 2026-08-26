<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'order_id',
        'pdf_path',
        'share_token',
        'generated_by',
        'generated_at',
        'whatsapp_opened_at',
        'whatsapp_opened_by',
        'whatsapp_open_count',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'whatsapp_opened_at' => 'datetime',
            'whatsapp_open_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Report $report) {
            if (empty($report->share_token)) {
                $report->share_token = Str::random(48);
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function whatsappOpenedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'whatsapp_opened_by');
    }

    public function getPublicUrlAttribute(): string
    {
        return route('public.reports.show', ['token' => $this->share_token]);
    }

    public function getPublicDownloadUrlAttribute(): string
    {
        return route('public.reports.download', ['token' => $this->share_token]);
    }
}
