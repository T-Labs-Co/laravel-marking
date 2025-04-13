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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;

/**
 * @property string $normalized
 * @property string $name
 * @property string $classification
 * @property int $weight
 */
class Mark extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'mark_id';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'classification',
        'normalized',
        'weight',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $attributes = [])
    {
        if ($connection = config('marking.connection')) {
            $this->setConnection($connection);
        }

        $table = config('marking.tables.marking_marks', 'marking_marks');
        $this->setTable($table);

        parent::__construct($attributes);
    }

    /**
     * Set the name attribute on the model.
     *
     * @param  string  $value
     */
    public function setNameAttribute($value)
    {
        $value = trim($value);
        $this->attributes['name'] = $value;
        if (!$this->normalized) {
            $this->attributes['normalized'] = normalize($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isRelation($key)
    {
        // Check for regular relation first
        if ($return = parent::isRelation($key)) {
            return $return;
        }

        // Check if the relation is defined via configuration
        $relatedClass = Arr::get(config('marking.markedModels'), $key);

        if ($relatedClass) {
            $relation = $this->markedModels($relatedClass);

            tap($relation->getResults(), function ($results) use ($key) {
                $this->setRelation($key, $results);
            });

            return true;
        }

        return false;
    }

    /**
     * Get the inverse of the polymorphic relation, via an attribute
     * defining the type of models to return.
     */
    protected function markedModels(string $class): MorphToMany
    {
        $table = config('marking.tables.marking_markables', 'marking_markables');

        return $this->morphedByMany($class, 'markable', $table, 'mark_id')
            ->withTimestamps()
            ->withPivot(['value', 'metadata'])
            ->withCasts([
                'metadata' => 'array',
            ]);
    }
}
