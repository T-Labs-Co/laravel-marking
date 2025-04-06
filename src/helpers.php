<?php


if (!function_exists('normalize')) {
    /**
     * Normalize a string.
     *
     * @param string $string
     *
     * @return string
     */
    function normalize(string $string): string
    {
        return call_user_func(config('marking.normalizer'), $string);
    }
}

if (! function_exists('normalize_mark_value')) {
    function normalize_mark_value($value = null)
    {
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

        if (!is_array($metadata)) {
            $metadata = [$metadata];
        }

        return $metadata;
    }
}
if (!function_exists('snake_case')) {
    /**
     * Convert a string to snake_case.
     *
     * @param string $string
     *
     * @return string
     */
    function snake_case(string $string): string
    {
        return implode('_', explode(' ', mb_strtolower($string)));
    }
}

if (! function_exists('normalize_mark_classification')) {
    function normalize_mark_classification(string $type = null): string|null
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
    function is_valid_mark_classification(string $type): bool
    {
        return in_array($type, mark_classifications());
    }
}

