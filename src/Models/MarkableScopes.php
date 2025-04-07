<?php

namespace TLabsCo\LaravelMarking\Models;

use Illuminate\Database\Eloquent\Builder;

trait MarkableScopes
{
    public function scopeClassification(Builder $builder, ?string $classification)
    {
        return $builder->where('classification', $this->normalizeMarkClassification($classification));
    }

    public function scopeDefaultClassification(Builder $builder)
    {
        return $builder->classification();
    }
}
