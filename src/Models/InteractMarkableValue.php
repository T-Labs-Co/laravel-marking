<?php

namespace TLabsCo\LaravelMarking\Models;

use Illuminate\Support\Arr;

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

        $positives = array_values(array_filter($valuesMap, fn($v) => $v > 0));

        return count($positives);
    }

    public function countMarkingValuesNegative($classification = null)
    {
        $valuesMap = $this->getMarkingValuesMap($classification, 'intval');

        $positives = array_values(array_filter($valuesMap, fn($v) => $v < 0));

        return count($positives);
    }

    public function countMarkingValuesNeutral($classification = null)
    {
        $valuesMap = $this->getMarkingValuesMap($classification, 'intval');

        $neutrals = array_values(array_filter($valuesMap, fn($v) => $v == 0));

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
