<?php

namespace App\Traits;

use Carbon\Carbon;

/**
 * Provides additional query scope methods to Eloquent models for searching, pagination, and date filtering.
 */
trait HasQueryHelper
{
    /**
     * Adds a search scope to the query.
     *
     * Allows filtering query results based on a keyword that matches any of the specified field names.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance.
     * @param string $fieldNames Comma-separated string of field names to search within.
     * @return \Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Adds a pagination scope to the query.
     *
     * If a 'page' request parameter is present, it paginates the results; otherwise, it retrieves all results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance.
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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

    /**
     * Adds a date filtering scope to the query.
     *
     * Filters query results based on a start and end date provided through request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance.
     * @param \Illuminate\Http\Request $request The current HTTP request instance containing date parameters.
     * @return \Illuminate\Database\Eloquent\Builder
     */
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
