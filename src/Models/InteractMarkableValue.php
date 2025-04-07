<?php

namespace TLabsCo\LaravelMarking\Models;

trait InteractMarkableValue
{
    public function getMarkingValuesMap($classification = null)
    {
        $marks = $this->marks->where('classification', $this->normalizeMarkClassification($classification))->all();

        if (is_array($marks)) {
            $marks = collect($marks);
        }

        $values = $marks->pluck('pivot.value', 'normalized')->toArray();

        return array_map(fn ($v) => normalize_mark_value($v, $classification), $values);
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
