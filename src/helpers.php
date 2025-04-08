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
use Illuminate\Support\Arr;

if (! function_exists('normalize')) {
    /**
     * Normalize a string.
     */
    function normalize(string $string): string
    {
        return call_user_func(config('marking.normalizer'), $string);
    }
}

if (! function_exists('normalize_mark_value')) {
    function normalize_mark_value($value = null, $classification = null)
    {
        // TODO: get caster
        $classification = normalize_mark_classification($classification);
        $caster = Arr::get(config('marking.values_caster'), $classification);

        if (is_callable($caster)) {
            return call_user_func($caster, $value);
        }

        $default = config('marking.default_value');

        if (is_null($value)) {
            return $default;
        }

        if (is_array($value)) {
            return \Illuminate\Support\Arr::first(\Illuminate\Support\Arr::dot($value));
        }

        return value($value);
    }
}

if (! function_exists('normalize_mark_metadata')) {
    function normalize_mark_metadata($metadata = null)
    {
        if (is_null($metadata)) {
            return $metadata;
        }

        if (is_object($metadata)) {
            return (array) $metadata;
        }

        if (! is_array($metadata)) {
            $metadata = [$metadata];
        }

        return $metadata;
    }
}
if (! function_exists('snake_case')) {
    /**
     * Convert a string to snake_case.
     */
    function snake_case(string $string): string
    {
        return implode('_', explode(' ', mb_strtolower($string)));
    }
}

if (! function_exists('normalize_mark_classification')) {
    function normalize_mark_classification(?string $type = null): ?string
    {
        $default = config('marking.default_classification');
        if (is_null($type)) {
            return $default;
        }

        if (is_valid_mark_classification($type)) {
            return $type;
        }

        return null;
    }
}

if (! function_exists('mark_classifications')) {
    function mark_classifications(): array
    {
        return config('marking.classifications');
    }
}

if (! function_exists('is_valid_mark_classification')) {
    function is_valid_mark_classification(?string $type = null): bool
    {
        if (is_null($type)) {
            return true;
        }

        return in_array($type, mark_classifications());
    }
}
