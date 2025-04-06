<?php

namespace TLabsCo\LaravelMarking\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TypeScope implements Scope
{
    /**
     * The type to filter by.
     */
    protected string $type;

    /**
     * Create a new TypeScope instance.
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('type', $this->type);
    }
}
