<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $emailSettings = [
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.gmail.com',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => 'password',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_from_address',
                'value' => '',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'MestaKara',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($emailSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name'
        ])->delete();
    }
};