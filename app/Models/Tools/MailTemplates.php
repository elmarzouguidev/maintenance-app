<?php

declare(strict_types=1);

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplates extends Model
{
    use HasFactory;

    protected $table="mail_templates";

    /**
     * @var string[]|array<int,string>
     */
    protected  $fillable = [
        'name',
        'content',
        'active'
    ];

    /**
     * @var string[]|array<int,string>
     */
    protected array $casts = [];

    // Relationships

    // Helper Methods
}
