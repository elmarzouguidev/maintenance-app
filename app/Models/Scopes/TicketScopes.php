<?php

namespace App\Models\Scopes;

use App\Constants\Etat;
use Carbon\Carbon;

trait TicketScopes
{
    public function scopeFiltersStatus($query, $status)
    {
        return $query
            ->whereIn('etat', [Etat::NON_REPARABLE, Etat::REPARABLE, Etat::NON_DIAGNOSTIQUER])
            ->where('status', $status);
    }

    public function scopeFiltersClient($query, $client)
    {
        return $query
            ->whereIn('etat', [Etat::NON_REPARABLE, Etat::REPARABLE, Etat::NON_DIAGNOSTIQUER])
            ->whereClientId($client);
    }

    public function scopeFiltersRetour($query, $retour)
    {
        return $query->where('is_retour', true);
    }

    public function scopeFiltersEtat($query, $etat)
    {
        return $query
            ->where('etat', $etat);
    }

    public function scopeFiltersDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            try {
                $start = Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay();
                $end = Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay();
                
                return $query->whereBetween('created_at', [$start, $end]);
            } catch (\Exception $e) {
                return $query;
            }
        }
        
        if ($startDate) {
            try {
                $start = Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay();
                return $query->where('created_at', '>=', $start);
            } catch (\Exception $e) {
                return $query;
            }
        }
        
        if ($endDate) {
            try {
                $end = Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay();
                return $query->where('created_at', '<=', $end);
            } catch (\Exception $e) {
                return $query;
            }
        }
        
        return $query;
    }

    public function scopeFiltersStartDate($query, $startDate)
    {
        if ($startDate) {
            try {
                $start = Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay();
                return $query->where('created_at', '>=', $start);
            } catch (\Exception $e) {
                return $query;
            }
        }
        return $query;
    }

    public function scopeFiltersEndDate($query, $endDate)
    {
        if ($endDate) {
            try {
                $end = Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay();
                return $query->where('created_at', '<=', $end);
            } catch (\Exception $e) {
                return $query;
            }
        }
        return $query;
    }

    public function scopeFiltersPeriods($query, $period)
    {
        if ($period == 1) {
            return $query->whereBetween(
                'created_at',
                [
                    now()->startOfYear()->startOfQuarter(),
                    now()->startOfYear()->endOfQuarter(),
                ]
            );
        }
        if ($period == 2) {
            return $query->whereBetween(
                'created_at',
                [
                    now()->startOfYear()->addMonths(3)->startOfQuarter(),
                    now()->startOfYear()->addMonths(3)->endOfQuarter(),
                ]
            );
        }
        if ($period == 3) {
            return $query->whereBetween(
                'created_at',
                [
                    now()->startOfYear()->addMonths(6)->startOfQuarter(),
                    now()->startOfYear()->addMonths(6)->endOfQuarter(),
                ]
            );
        }
        if ($period == 4) {
            return $query->whereBetween(
                'created_at',
                [
                    now()->startOfYear()->addMonths(9)->startOfQuarter(),
                    now()->startOfYear()->addMonths(9)->endOfQuarter(),
                ]
            );
        }
        
        return $query;
    }
}
