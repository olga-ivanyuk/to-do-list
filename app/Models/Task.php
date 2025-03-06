<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $table = 'tasks';
    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }

    /**
     * @return void
     */
    public function generateAccessToken(): void
    {
        $this->access_token = Str::random(32);
        $this->access_expires_at = now()->addHours(24);
        $this->save();
    }
}
