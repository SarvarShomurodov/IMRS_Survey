<?php
// database/migrations/2024_01_01_000006_create_survey_responses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('activity_type_id');
            $table->string('company_name', 255);
            $table->text('company_address')->nullable();
            $table->integer('employee_count');
            $table->enum('headcount_change', ['oshdi', 'ozgarmadi', 'kamaydi']);
            $table->enum('headcount_six_change', ['oshdi', 'ozgarmadi', 'kamaydi']);
            $table->integer('survey_period_year')->default(2025);
            $table->integer('survey_period_quarter')->default(1);
            $table->json('additional_data')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('restrict');
            $table->foreign('activity_type_id')->references('id')->on('activity_types')->onDelete('restrict');
            
            // Unique constraint - bir user bitta davr uchun bitta response
            $table->unique(['user_id', 'survey_period_year', 'survey_period_quarter'], 'user_period_unique');
            
            // Indekslar
            $table->index(['region_id', 'district_id', 'activity_type_id']);
            $table->index(['survey_period_year', 'survey_period_quarter']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_responses');
    }
};