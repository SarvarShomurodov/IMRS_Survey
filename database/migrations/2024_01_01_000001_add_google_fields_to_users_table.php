<?php
// Avval barcha migrationlarni tozalash va qaytadan yaratish

// 1. Eski migrationlarni o'chirish
// php artisan migrate:rollback --all
// php artisan migrate:fresh

// 2. Migration fayllarini to'g'ri tartibda yarating:

// database/migrations/2024_01_01_000001_add_google_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->unique()->nullable()->after('email');
            $table->string('avatar')->nullable()->after('google_id');
            $table->boolean('is_admin')->default(false)->after('avatar');
            $table->timestamp('last_survey_at')->nullable()->after('is_admin');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar', 'is_admin', 'last_survey_at']);
        });
    }
};