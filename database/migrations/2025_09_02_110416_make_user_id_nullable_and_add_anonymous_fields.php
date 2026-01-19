<?php
// Yangi migration - faqat zarur o'zgarishlar
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            // 1. User_id ni nullable qilish
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // 2. Faqat yangi maydonlarni qo'shish
            $table->string('respondent_name', 255)->nullable()->after('id');
            $table->string('respondent_email', 255)->nullable()->after('respondent_name');
            $table->string('ip_address', 45)->nullable()->after('additional_data');
            
            // Unique constraint ni QOLDIRISH - olib tashlamaslik
        });
    }

    public function down()
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['respondent_name', 'respondent_email', 'ip_address']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};