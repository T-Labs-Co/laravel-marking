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
namespace TLabsCo\LaravelMarking\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DefaultClassificationScope extends ClassificationScope
{
    /**
     * Create a new ClassificationScope instance.
     */
    public function __construct()
    {
        parent::__construct(static::getDefaultClassification());
    }

    /**
     * Get the default classification.
     */
    public static function getDefaultClassification(): string
    {
        return config('marking.default_classification', 'general');
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Apply a default classification filter (e.g., 'general')
        $builder->where('classification', static::getDefaultClassification());
    }
}
