<?php

namespace App\Collections\Report;

use Illuminate\Database\Eloquent\Collection;

class ReportCollection extends Collection
{

    /**
     * @return ReportCollection
     */
    public function groupByStatus(): ReportCollection
    {
        return $this->groupBy(function ($report) {

            if ($report->status === 'ouvert') {
                return 'ouvert';
            } else {
                return 'envoyer';
            }
        });
    }
}
