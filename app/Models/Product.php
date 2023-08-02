<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public function scopeApproved(EloquentBuilder $query): void
    {
        $query->where('status', ProductStatus::APPROVED);
    }

    public function scopePending(EloquentBuilder $query): void
    {
        $query->where('status', ProductStatus::PENDING);
    }

    public function scopeRejected(EloquentBuilder $query): void
    {
        $query->where('status', ProductStatus::REJECTED);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function status(): string
    {
        return ProductStatus::fromValue($this->status);
    }
}
