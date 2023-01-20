<?php

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Support\Str;

class BaseRepository
{
    protected $model;
    protected array $searchColumns;
    protected array $selects;

    public function __construct($model, $searchColumns = [], $selects = [])
    {
        $this->model = $model;
        $this->searchColumns = $searchColumns;
        $this->selects = $selects;
    }

    public function get(
        $filters = [],
        $search = false,
        $with = false,
        $whereHas = false,
        $selects = false,
        $range = false,
        $rangeBy = "created_at",
        $orderBy = "id",
        $order = "asc",
        $withPagination = true,
        $count = 8
    ) {
        $filters = $this->prepareFilters($filters);
        $query = $this->model->where($filters);
        $query = $this->doSelect($query, $selects);
        $search == false ? $query : $query = $this->search($query, $search);
        $range == false ? $query : $query = $this->dateFilter($query, $range, $rangeBy);
        $with == false ? $query : $query = $query->with($with);
        $query->orderBy($orderBy, $order);
        $whereHas == false ? $query : $query = $query->whereHas($whereHas);
        $withPagination == true ? $query = $query->paginate($count) : $query = $query->get();
        return $query;
    }

    public function show($by, $selects = false, $with = false, $column = "id")
    {
        $query = $this->model->where($column, $by);
        $query = $this->doSelect($query, $selects);
        $with == false ? $query : $query = $query->with($with);
        return $query->first();
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($by, $data, $column = "id")
    {
        $this->model->where($column, $by)->update($data);
        return $this->model->where($column, $by)->first();
    }

    public function delete($by, $column = "id")
    {
        $this->model->where($column, $by)->delete();
    }


    public function changeStatus($record): bool
    {
        $status = !$record->is_active;
        $record->update(["is_active" => $status]);
        return $status;
    }

    public function count($filters = [])
    {
        $filters = $this->prepareFilters($filters);
        $query = $this->model->where($filters);
        return $query->count();
    }

    public function prepareFilters($filters): array
    {
        return array_diff($filters, ["*"]);
    }

    public function doSelect($query, $selects)
    {
        if ($selects == false || $selects == []) {
            $this->selects == [] ? $query: $query = $query->select($this->selects);
            return $query;
        }
        return $query->select($selects);
    }

    public function search($current, $value)
    {
        $cols = $this->searchColumns;
        return $current->where(function ($query) use ($cols, $value) {
            $query = $query->where($cols[0], 'LIKE', '%' . $value . '%');
            $first = true;
            foreach ($cols as $column) {
                if ($first) {
                    $first = false;
                    continue;
                }
                $query = $query->orWhere($column, 'LIKE', '%' . $value . '%');
            }
        });
    }

    public function dateFilter($records, $range, $by)
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        $weekEnd = Carbon::now()->endOfWeek(Carbon::FRIDAY);
        $now = Carbon::now();
        if ($range == "today") {
            $records = $records->whereDate($by, Carbon::today());
        }
        if ($range == "yesterday") {
            $records = $records->whereDate($by, Carbon::yesterday());
        }
        if ($range == "this-week") {
            $records = $records->whereBetween($by, [$weekStart, $weekEnd]);
        }
        if ($range == "prev-week") {
            $records = $records->whereBetween($by, [$weekStart->subWeek(), $weekEnd->subWeek()]);
        }
        if ($range == "this-month") {
            $records = $records->whereMonth($by, $now->month)->whereYear($by, $now->year);
        }
        if ($range == "prev-month") {
            $records = $records->whereMonth($by, $now->subMonth()->month)->whereYear($by, $now->year);
        }
        if (Str::contains($range, ',')) {
            $dates = explode(",", $range);
            $records = $records
                ->whereBetween($by, [Carbon::parse($dates[0]), Carbon::parse($dates[1])->addDay()]);
        }
        return $records;
    }
}
