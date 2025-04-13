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

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use TLabsCo\LaravelMarking\Events\ModelMarked;
use TLabsCo\LaravelMarking\Events\ModelUnmarked;
use TLabsCo\LaravelMarking\Exceptions\InvalidMarkClassificationException;
use TLabsCo\LaravelMarking\MarkingService;

trait Markable
{
    use MarkableScopes;

    private static $pivotFields = ['value', 'metadata'];

    public static function defaultClassification()
    {
        return null;
    }

    public static function bootMarkable(): void
    {
        static::deleting(function ($model) {
            if (! method_exists($model, 'runSoftDelete') || $model->isForceDeleting()) {
                $model->unmark();
            }
        });
    }

    /**
     * Get a collection of all tags the model has.
     */
    public function marks(): MorphToMany
    {
        $model = config('marking.model');
        $table = config('marking.tables.marking_markables', 'marking_markables');

        return $this->morphToMany($model, 'markable', $table, 'markable_id', 'mark_id')
            ->withPivot(static::$pivotFields)
            ->withCasts([
                'metadata' => 'array',
            ])
            ->withTimestamps();
    }

    /**
     * Attach one or multiple marks to the model.
     *
     * @param  string|array  $names
     */
    public function marking($names, $pivotData = [], $classification = null): self
    {
        $marks = MarkingService::make()->buildMarkArray($names);

        foreach ($marks as $mark) {
            $this->markingOne($mark, $pivotData, $classification);
            $this->load('marks');
        }

        event(new ModelMarked($this, $marks));

        return $this;
    }

    /**
     * Attach one or more existing marks to a model,
     * identified by the mark's IDs.
     *
     * @param  int|int[]  $ids
     * @return $this
     */
    public function markingById($ids): self
    {
        if (! is_array($ids)) {
            $ids = [$ids];
        }

        $marks = Mark::whereIn('mark_id', $ids ?? [-1])->get();
        $classifications = $marks->pluck('classification')->unique()->values()->toArray();
        foreach ($classifications as $classification) {
            $names = collect($marks->where('classification', $classification)->pluck('normalized')->toArray());
            $this->marking($names, classification: $classification);
        }

        return $this;
    }

    public function markingOne(string|array $name, $pivotData = [], $classification = null): void
    {
        /** @var Mark $mark */
        $mark = Mark::firstOrCreate([
            // 'name' => $this->normalizeMarkName($name),
            'normalized' => normalize($name),
            'classification' => $this->normalizeMarkClassification($classification),
        ]);

        $mark->name = $this->normalizeMarkName($name);

        // Extract pivot fields value
        if (is_array($name) && empty($pivotData)) {
            $pivotData = Arr::only($name, static::$pivotFields);
        }

        $key = $mark->getKey();

        if (! $this->getAttribute('marks')->contains($key)) {
            if (! empty($pivotData)) {
                $pivotData = Arr::only($pivotData, static::$pivotFields);
            } else {
                $pivotData = [];
            }
            $this->marks()->attach($key, $pivotData);
        }
    }

    public function unmarking($names, $classification = null)
    {
        $marks = MarkingService::make()->buildMarkArray($names);

        foreach ($marks as $name) {
            $this->unmarkingOne($name, $classification);
            $this->load('marks');
        }

        event(new ModelUnmarked($this, $marks));

        return $this;
    }

    /**
     * Detach one or more existing marks to a model,
     * identified by the mark's IDs.
     *
     * @param  int|int[]  $ids
     * @return $this
     */
    public function unmarkingById($ids): self
    {
        if (! is_array($ids)) {
            $ids = [$ids];
        }

        $marks = Mark::whereIn('mark_id', $ids ?? [-1])->get();
        $classifications = $marks->pluck('classification')->unique()->values()->toArray();
        foreach ($classifications as $classification) {
            $names = collect($marks->where('classification', $classification)->pluck('normalized')->toArray());
            $this->unmarking($names, $classification);
        }

        return $this;
    }

    /**
     * @param  string  $name
     */
    protected function unmarkingOne(mixed $name, $classification = null): void
    {
        /** @var Mark $mark */
        $mark = Mark::where([
            'normalized' => normalize($name),
            'classification' => $this->normalizeMarkClassification($classification),
        ])->first();

        if ($mark) {
            $this->marks()->detach($mark);
        }
    }

    /**
     * Remove all marks from the model and assign the given ones.
     *
     * @param  string|array  $names
     */
    public function remarking($names, $classification = null): self
    {
        $this->demarking($classification);

        return $this->marking($names, $classification);
    }

    /**
     * Remove all marks from the model.
     */
    public function demarking($classification = null): self
    {
        if ($classification) {
            $marks = $this->marks->where('classification', $classification)->pluck('normalized')->toArray();
            $this->unmarking($marks, $classification);
        } else {
            $this->marks()->sync([]);
        }
        $this->load('marks');

        return $this;
    }

    protected function normalizeMarkName(mixed $value)
    {
        if ($value instanceof Mark) {
            return $value->name;
        }

        if (is_string($value)) {
            return $value;
        }

        if ($value && is_array($value) && isset($value['name'])) {
            return $value['name'];
        }

        if (is_array($value) && Arr::isAssoc($value)) {
            return array_key_first($value);
        }

        return value($value);
    }

    protected function normalizeMarkValue(mixed $value, $classification = null)
    {
        // default value is 1 - support count
        if ($value && is_array($value) && isset($value['value'])) {
            $value = $value['value'];
        }

        return normalize_mark_value($value, $classification);
    }

    protected function normalizeMarkMetadata(mixed $value)
    {
        $metadata = null;

        if ($value && is_array($value) && isset($value['metadata'])) {
            $metadata = $value['metadata'];
        }

        return normalize($metadata);
    }

    protected function normalizeMarkClassification(?string $classification)
    {
        $classification = $classification ?? static::defaultClassification();

        if (! is_valid_mark_classification($classification)) {
            throw new InvalidMarkClassificationException($classification);
        }

        return normalize_mark_classification($classification);
    }
}
