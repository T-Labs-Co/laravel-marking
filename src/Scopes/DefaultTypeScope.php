<?php

// filepath: /Users/tyhh/Workspaces/dbcsoft-labs/t-labs-co/t-labs-packages/packages/t-labs-co/laravel-marking/src/Scopes/TypeScope.php

namespace TLabsCo\LaravelMarking\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DefaultTypeScope extends TypeScope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Apply a default type filter (e.g., 'general')
        $builder->where('type', config('marking.default_type', 'general'));
    }
}
