<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasQueryHelper
{
    public static function scopeSearch($query, string $fieldNames = 'name')
    {
        $field = request()->keyword;

        return $query->when(!is_null($field), function ($query) use ($field, $fieldNames) {
            $fieldNames = explode(',', $fieldNames);
            $query->where(function ($query) use ($field, $fieldNames) {
                foreach ($fieldNames as $fieldName) {
                    $query->orWhere($fieldName, 'like', "%{$field}%");
                }
            });
        });
    }

    public static function scopePaged($query)
    {
        $page = request()->page;

        return $query->when(is_null($page), function ($query) {
            return $query->get();
        })
            ->when(!is_null($page), function ($query) {
                return $query->paginate(15);
            });
    }

    public static function scopeDateFilter($query, $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        return $query->when(!is_null($startDate), function ($query) use ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        })
            ->when(!is_null($endDate), function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            });
    }
}
