<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'type', 'value', 'percent_off'];

    public static function findByCode($code)
    {
        return self::whereCode($code)->first();
    }

    public function discount($total)
    {
        if ($this->type === 'fixed') {
            return $this->value;
        } elseif ($this->type === 'percent') {
            return round(($this->percent_off / 100) * $total);
        } else {
            return 0;
        }
    }
}
