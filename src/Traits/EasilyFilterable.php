<?php

namespace Exidus\Hydra\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

trait EasilyFilterable
{

    public function scopeFilterEasily(Builder $query, Request $request, array $filters)
    {
        foreach ($filters as $key => $filter) {

            if (is_array($filter)) {
                $id = $key;
                $column = !empty($filter['column']) ? $filter['column'] : $key;
            } else {
                $id = $filter;
                $column = $filter;
            }

            if ($request->input($id) || $request->input($id) === "0") {
                $value = $request->input($id);

                $not = false;
                $basic = false;

                if (is_array($filter)) {

                    // Prep function
                    if (!empty($filter['prep'])) {

                        if ($filter['prep'] == 'datetime') {
                            // TODO As DateTime

                        } else {
                            // Custom
                            $value = $filter['prep']($value);
                        }
                    }

                    // Check for not
                    $not = in_array('not', $filter);

                    if (!empty($filter['related'])) {
                        // Filter by related model

                        $query->{!$not ? 'whereHas' : 'whereDoesntHave'}($filter['related'],
                            function (Builder $query) use ($id, $filter, $request) {
                                // Remove "related" value from array
                                unset($filter['related']);
                                // Apply filters to related model
                                $query->filterEasily($request, [$id => $filter]);
                            });

                    } elseif (in_array('like', $filter)) {
                        // Filter using LIKE

                        $query->where($column, !$not ? 'like' : 'not like', '%' . $value . '%');

                    } elseif (in_array('having', $filter)) {
                        // Filter using HAVING

                        $query->having($column, !$not ? '=' : '!=', $value);

                    } elseif (!empty($filter['custom'])) {
                        // Custom function

                        $query->when(is_callable($filter['custom']), $filter['custom']);

                    } elseif (($from = in_array('date_from', $filter)) || in_array('date_to', $filter)) {
                        // Filter by date range

                        $query->where($column, $from ? '>=' : '<=', Carbon::make($value)->utc());

                    } elseif (($min = in_array('min', $filter)) || in_array('max', $filter)) {
                        // Filter min / max

                        $query->where($column, $min ? '>=' : '<=', $value);

                    } else {
                        $basic = true;
                    }
                } else {
                    $basic = true;
                }

                if ($basic) {
                    // Basic filter
                    $query->where($column, !$not ? '=' : '!=', $value);
                }
            }

        }
        return $query;
    }

}
