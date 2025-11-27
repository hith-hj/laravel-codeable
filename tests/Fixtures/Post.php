<?php

declare(strict_types=1);

namespace Codeable\Tests\Fixtures;

use Codeable\Traits\HasCodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Post extends Model
{
    use HasCodes;

    public $timestamps = false;

    protected $table = 'posts';

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
