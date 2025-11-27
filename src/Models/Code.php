<?php

declare(strict_types=1);

namespace Codeable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Code extends Model
{
    protected $fillable = [
        'codeable_id',
        'codeable_type',
        'type',
        'code',
        'expire_at',
    ];

    protected function casts(): array
    {
        return [
            'code' => 'int',
            'expire_at' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        return $this->expire_at->gt(now());
    }

    public function isExpired(): bool
    {
        return ! $this->isValid();
    }

    public function codeable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__);
    }
}
