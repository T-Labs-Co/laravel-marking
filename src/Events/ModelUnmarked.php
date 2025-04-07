<?php

namespace TLabsCo\LaravelMarking\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ModelUnmarked
{
    public function __construct(
        public Model $model,
        public Collection|array $marks
    ) {}
}
