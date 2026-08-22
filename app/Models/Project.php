<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = [];

    protected $casts = [
        'badges' => 'array',
        'distances' => 'array',
        'features' => 'array',
        'gallery' => 'array',
        'is_published' => 'boolean',
    ];

    /** @return HasMany<HouseType, $this> */
    public function houseTypes(): HasMany
    {
        return $this->hasMany(HouseType::class)->orderBy('sort');
    }

    /** @return HasMany<Testimonial, $this> */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /** @param  Builder<$this>  $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort');
    }
}
