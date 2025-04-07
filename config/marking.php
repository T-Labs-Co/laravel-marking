<?php

use Illuminate\Support\Arr;

// config for TLabsCo/LaravelMarking
return [

    /**
     * List of characters that can delimit the labels passed to the
     * mark() / unmark() / etc. functions.
     */
    'delimiters' => ',;',

    /**
     * Character used to delimit tag lists returned in the
     * markList, markListNormalized, etc. attributes.
     */
    'glue' => ',',

    /**
     * The default classification to use when no classification is
     * specified.  This is used when the model does not have a
     * default classification defined.
     */
    'classifications' => array_merge(
        ['general'],
        Arr::dot(explode(',', env('LARAVEL_MARKING_CLASSIFICATIONS', '')))
    ),

    /**
     * The default classification to use when no classification is
     * specified.  This is used when the model does not have a
     * default classification defined.
     */
    'default_classification' => env('LARAVEL_MARKING_CLASSIFICATION_DEFAULT', 'general'),

    /**
     * The default value to use when no value is specified.  This
     * is used when the model does not have a default value defined.
     */
    'default_value' => env('LARAVEL_MARKING_VALUE_DEFAULT', 1), // using to count or sum point

    /**
     * The value will be nomalized to the type of the value
     * defined in the array.  The key is the classification name
     * and the value is the type of the value.
     * 
     * classification =>  a closure function, or a callable, e.g. ['Classname', 'method'].
     */
    'values_caster' => [
        'general' => 'strval', //
    ],

    /**
     * Method used to "normalize" label names.  Can either be a global function name,
     * a closure function, or a callable, e.g. ['Classname', 'method'].
     */
    'normalizer' => 'snake_case',

    /**
     * The database connection to use for the Mark model and associated tables.
     * By default, we use the default database connection, but this can be defined
     * so that all the label-related tables are stored in a different connection.
     */
    'connection' => null,

    /**
     * How to handle passing empty values to the scope queries.  When set to false,
     * the scope queries will return no models.  When set to true, passing an empty
     * value to the scope queries will throw an exception instead.
     */
    'throwEmptyExceptions' => false,

    /**
     * 
     * Reverse model list from markedModels.  
     * 
     * This is used to define the reverse relationship for the marked models.
     *
     *  'markedModels' => [
     *      'categories' => \App\Category::class
     *  ]
     *
     * You will be able to do:
     *
     *  $categories = Marking::findByName('trending')->categories;
     *
     * to get a collection of all the Posts that are marked "label".
     */
    'markedModels' => [],

    /**
     * The model used to store the tags in the database.  You can
     * create your own class that extends the package's Marking model,
     * then update the configuration below.
     */
    'model' => \TLabsCo\LaravelMarking\Models\Mark::class,

    /**
     * The tables used to store the marks in the database.  You can
     * publish the package's migrations and use custom names.
     */
    'tables' => [
        'marking_marks' => 'marking_marks',
        'marking_markables' => 'marking_markables',
    ],
];
