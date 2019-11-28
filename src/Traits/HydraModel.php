<?php

namespace Exidus\Hydra\Traits;

use Illuminate\Support\Facades\Auth;

trait HydraModel
{

    protected static $validationFields;

    public static function getTableName()
    {
        return ((new self)->getTable());
    }

    public static function rules(array $data = null)
    {
        return [];
    }

    public static function rulesOnly(array $filter, array $data = null)
    {
        return array_intersect_key(self::rules($data), array_flip($filter));
    }

    public static function rulesData(array $data = null, string $field = null)
    {
        if (is_array($data) && isset($data[$field])) {
            return $data[$field];
        }
        return null;
    }

    public function simpleAuth($field = 'user_id', $value = null)
    {
        if (!$value) {
            $value = Auth::id();
        }
        if ($this->{$field} != $value) {
            abort(403, 'Access denied.');
        }
        return $this;
    }

}
