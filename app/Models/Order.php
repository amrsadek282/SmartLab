<?php

namespace App\Models;

use App\Services\EgyptianPhoneNormalizer;
use App\Services\WhatsAppReportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'patient_id',
        'appointment_id',
        'status',
        'sample_status',
        'sample_collected_at',
        'sample_received_at',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'sample_collected_at' => 'datetime',
            'sample_received_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeSamplePending(Builder $query): Builder
    {
        return $query->where('sample_status', 'pending_collection');
    }

    public function scopeSampleCollected(Builder $query): Builder
    {
        return $query->where('sample_status', 'collected');
    }

    public function scopeSampleReceived(Builder $query): Builder
    {
        return $query->where('sample_status', 'received_in_lab');
    }

    /**
     * Check if all ordered test items have entered results.
     */
    public function isResultsComplete(): bool
    {
        if ($this->orderItems->isEmpty()) {
            return false;
        }

        foreach ($this->orderItems as $item) {
            if (! $item->result || empty($item->result->result_text)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the report is ready for viewing/sharing.
     */
    public function isReportReady(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the associated patient has a valid WhatsApp-compatible phone number.
     */
    public function hasValidWhatsappPhone(): bool
    {
        return EgyptianPhoneNormalizer::isValidEgyptianMobile($this->patient?->phone)
            || ! empty(EgyptianPhoneNormalizer::normalize($this->patient?->phone));
    }

    /**
     * Get normalized phone number string for WhatsApp.
     */
    public function getNormalizedPatientPhone(): ?string
    {
        return EgyptianPhoneNormalizer::normalize($this->patient?->phone);
    }

    /**
     * Get formatted display string for patient's phone number.
     */
    public function getFormattedPatientPhone(): string
    {
        return EgyptianPhoneNormalizer::formatDisplay($this->patient?->phone);
    }

    /**
     * Get formatted WhatsApp click-to-chat URL for patient.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        return WhatsAppReportService::generateClickToChatUrl($this);
    }
}
