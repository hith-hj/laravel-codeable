<?php

declare(strict_types=1);

namespace Codeable\Tests\Fixtures;

use Codeable\Traits\HasCodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class User extends Model
{
    use HasCodes;

    public $timestamps = false;

    protected $table = 'users';

    protected $guarded = [];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
