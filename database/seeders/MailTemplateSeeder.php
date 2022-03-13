<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MailTemplateSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $contents = file_get_contents(resource_path("views/theme/Emails/mail_template.blade.php"));
        DB::table('mail_templates')->insert([
            'name' => 'test',
            'content' => $contents
        ]);

    }
}
