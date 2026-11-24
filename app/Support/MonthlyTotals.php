<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Monthly totals for the charts of the report and client pages (<x-charts.monthly>): the sum of
 * a column per month of created_at, oldest month first. Replaces the "group_by_date" charts of
 * the removed laraveldaily/laravel-charts package (BACKLOG.md BL-12, BL-67).
 */
class MonthlyTotals
{
    /**
     * @param  Builder<Model>  $query  the rows to sum, already filtered
     * @return array{title: string, type: string, labels: list<string>, values: list<float>}
     */
    public static function chart(string $title, string $type, Builder $query, string $column): array
    {
        $rows = $query->toBase()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, SUM({$column}) as total")
            ->whereNotNull('created_at')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'title' => $title,
            'type' => $type,
            'labels' => $rows->map(fn (object $row): string => Str::ucfirst(Carbon::createFromFormat('!Y-m', $row->period)->translatedFormat('F Y')))->all(),
            'values' => $rows->map(fn (object $row): float => round((float) $row->total, 2))->all(),
        ];
    }
}
