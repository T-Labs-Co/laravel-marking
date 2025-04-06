<?php

namespace TLabsCo\LaravelMarking\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TLabsCo\LaravelMarking\Marking
 */
class Marking extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TLabsCo\LaravelMarking\Marking::class;
    }
}
