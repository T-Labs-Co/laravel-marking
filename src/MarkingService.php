<?php

namespace TLabsCo\LaravelMarking;

use Cviebrock\EloquentTaggable\Models\Tag;
use Illuminate\Support\Collection as BaseCollection;
use TLabsCo\LaravelMarking\Models\Mark;

class MarkingService
{
    const DEFAULT_TYPE = 'general';

    /**
     * @var Mark
     */
    protected $markModel;

    protected $defaultType;

    public function __construct()
    {
        $this->markModel = config('marking.model', Mark::class);
        $this->defaultType = config('marking.default_type', self::DEFAULT_TYPE);
    }

    /**
     * @return MarkingService
     */
    public static function make()
    {
        return app()->make(MarkingService::class);
    }

    /**
     * Convert a delimited string into an array of tag strings.
     *
     * @param  string|array|Mark|\Illuminate\Support\Collection  $marks
     *
     * @throws \ErrorException
     */
    public function buildMarkArray($marks): array
    {
        if (is_array($marks)) {
            $array = $marks;
        } elseif ($marks instanceof Mark) {
            $array = [$marks->normalized];
        } elseif ($marks instanceof BaseCollection) {
            $array = $this->buildMarkArray($marks->all());
        } elseif (is_string($marks)) {
            $array = preg_split(
                '#['.preg_quote(config('marking.delimiters'), '#').']#',
                $marks,
                -1,
                PREG_SPLIT_NO_EMPTY
            );
        } else {

            throw new \ErrorException(
                __CLASS__.'::'.__METHOD__.' expects parameter 1 to be string, array, Tag or Collection; '.
                gettype($marks).' given'
            );
        }

        return array_filter(
            array_map('trim', $array)
        );
    }

    /**
     * Convert a delimited string into an array of normalized tag strings.
     *
     * @param  string|array|Mark|\Illuminate\Support\Collection  $marks
     *
     * @throws \ErrorException
     */
    public function buildMarkArrayNormalized($marks): array
    {
        $tags = $this->buildMarkArray($marks);

        return array_map([$this, 'normalize'], $marks);
    }
}
