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

namespace TLabsCo\LaravelMarking\Models;

trait InteractMarkableValue
{
    public function getMarkingValuesMap($classification = null, $callable = null)
    {
        $marks = $this->marks->where('classification', $this->normalizeMarkClassification($classification))->all();

        if (is_array($marks)) {
            $marks = collect($marks);
        }

        $values = $marks->pluck('pivot.value', 'normalized')->toArray();

        $values = array_map(fn ($v) => normalize_mark_value($v, $classification), $values);

        if ($callable && is_callable($callable)) {
            $values = array_map(fn ($v) => $callable($v), $values);
        }

        return $values;
    }

    public function getMarkingValuesMapAndGroup()
    {
        $classifications = $this->marks->pluck('classification')->unique()->values()->toArray();

        $all = [];

        foreach ($classifications as $classification) {
            $all[$classification] = $this->getMarkingValuesMap($classification);
        }

        return $all;
    }

    public function countMarkingValuesPositive($classification = null)
    {
        $valuesMap = $this->getMarkingValuesMap($classification, 'intval');

        $positives = array_values(array_filter($valuesMap, fn ($v) => $v > 0));

        return count($positives);
    }

    public function countMarkingValuesNegative($classification = null)
    {
        $valuesMap = $this->getMarkingValuesMap($classification, 'intval');

        $positives = array_values(array_filter($valuesMap, fn ($v) => $v < 0));

        return count($positives);
    }

    public function countMarkingValuesNeutral($classification = null)
    {
        $valuesMap = $this->getMarkingValuesMap($classification, 'intval');

        $neutrals = array_values(array_filter($valuesMap, fn ($v) => $v == 0));

        return count($neutrals);
    }

    public function sumMarkingValues($classification = null)
    {
        $valuesMap = $this->getMarkingValuesMap($classification, 'intval');

        return collect($valuesMap)->values()->sum();
    }

    public function updateMarkingValue($mark, $value, $classification = null)
    {
        /** @var Mark $mark */
        $mark = Mark::firstOrCreate([
            'name' => $this->normalizeMarkName($mark),
            'classification' => $this->normalizeMarkClassification($classification),
        ]);

        $key = $mark->getKey();

        if ($this->getAttribute('marks')->contains($key)) {
            $this->marks()->detach($key);
        }

        $this->marks()->attach($key, ['value' => $this->normalizeMarkValue($value, $classification)]);
        $this->load('marks');

        return $this;
    }
}
