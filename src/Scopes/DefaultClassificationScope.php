<?php

namespace TLabsCo\LaravelMarking\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DefaultClassificationScope extends ClassificationScope
{
    /**
     * Create a new ClassificationScope instance.
     */
    public function __construct()
    {
        parent::__construct(static::getDefaultClassification());
    }

    /**
     * Get the default classification.
     */
    public static function getDefaultClassification(): string
    {
        return config('marking.default_classification', 'general');
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Apply a default classification filter (e.g., 'general')
        $builder->where('classification', static::getDefaultClassification());
    }
}
