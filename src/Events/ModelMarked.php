<?php

namespace TLabsCo\LaravelMarking\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ModelMarked
{
    public function __construct(
        public Model $model,
        public Collection|array $marks
    ) {}
}
