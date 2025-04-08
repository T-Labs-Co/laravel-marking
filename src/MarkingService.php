<?php

/*
 * This file is a part of package t-co-labs/laravel-marking
 *
 * (c) T.Labs & Co.
 * Contact for Work: T. <hongty.huynh@gmail.com>
 *
 * Got a PHP or Laravel project? We're your go-to team! We can help you:
 *   - Architect the perfect solution for your specific needs.
 *   - Get cleaner, faster, and more efficient code.
 *   - Boost your app's performance through refactoring and optimization.
 *   - Build your project the right way with Laravel best practices.
 *   - Get expert guidance and support for all things Laravel.
 *   - Ensure high-quality code through thorough reviews.
 *   - Provide leadership for your team and manage your projects effectively.
 *   - Bring in a seasoned Technical Lead.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TLabsCo\LaravelMarking;

use Illuminate\Support\Collection;
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
     * Convert a delimited string into an array of mark strings.
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
        } elseif ($marks instanceof Collection) {
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
                __CLASS__.'::'.__METHOD__.' expects parameter 1 to be string, array, Mark or Collection; '.
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
