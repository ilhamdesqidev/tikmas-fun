<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\WahanaImage;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // General Settings
        Setting::set('site_name', 'MestaKara', 'text', 'general');
        Setting::set('site_tagline', 'Berlibur Dengan Wahana', 'text', 'general');
        Setting::set('default_language', 'id', 'text', 'general');
        Setting::set('timezone', 'Asia/Jakarta', 'text', 'general');

        // Hero Section
        Setting::set('hero_title', 'Berlibur Dengan', 'text', 'hero');
        Setting::set('hero_subtitle', 'Wahana', 'text', 'hero');
        Setting::set('hero_description', 'Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas Bersama Keluarga Tercinta Dengan Harga Tiket Masuk yang Terjangkau dan Dapatkan Berbagai Promo Menarik Setiap Bulannya', 'textarea', 'hero');
        Setting::set('hero_cta_text', 'Dapatkan Promo', 'text', 'hero');

        // About Section
        Setting::set('about_title', 'Tentang', 'text', 'about');
        Setting::set('about_subtitle', 'Kami', 'text', 'about');
        Setting::set('about_question', 'Kenapa memilih Wahana kami?', 'text', 'about');
        Setting::set('about_content_1', 'MestaKara adalah penyedia wahana yang didirikan dengan cinta dan dedikasi untuk menghadirkan pengalaman wahana terbaik. Kami percaya bahwa setiap tawa dapat menciptakan kenangan indah yang akan diingat selamanya.', 'textarea', 'about');
        Setting::set('about_content_2', 'Wahana kami didirikan langsung di tengah perkebunan terbaik dan ditata dengan presisi yang sempurna. Setiap wahana yang kami sediakan adalah hasil dari perpaduan tradisi dan kualitas premium.', 'textarea', 'about');
        Setting::set('about_content_3', 'Dengan lebih dari 20 wahana menarik, fasilitas lengkap, dan staff berpengalaman, kami siap memberikan pengalaman liburan yang tak terlupakan untuk seluruh keluarga.', 'textarea', 'about');

        // Website Settings
        Setting::set('website_description', 'Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas', 'textarea', 'website');
        Setting::set('primary_color', '#CFD916', 'color', 'website');
        Setting::set('footer_text', '© 2025 Tiketmas. All rights reserved.', 'text', 'website');
        Setting::set('maintenance_mode', '0', 'boolean', 'website');


        $this->command->info('Settings seeded successfully!');
    }
}