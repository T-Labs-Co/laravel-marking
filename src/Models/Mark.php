<?php

namespace TLabsCo\LaravelMarking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $normalized
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
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'classification',
        'normalized',
        'weight'
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @inheritdoc
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
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $value = trim($value);
        $this->attributes['name'] = $value;
        $this->attributes['normalized'] = normalize($value);
    }

}
