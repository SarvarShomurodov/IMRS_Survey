<?php
// database/migrations/2024_01_01_000008_create_response_future_demand_skills_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('response_future_demand_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_response_id');
            $table->unsignedBigInteger('skill_id');
            $table->integer('expected_count')->nullable();
            
            // Yangi ustunlar
            $table->string('education_level')->nullable(); // umumiy_orta, orta_maxsus, oliy
            $table->string('experience_level')->nullable(); // 0, 1-2, 3-5, 5+
            $table->string('gender_preference')->nullable(); // erkak, ayol, farq_qilmaydi
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('survey_response_id')->references('id')->on('survey_responses')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('restrict');
            
            // Unique constraint
            $table->unique(['survey_response_id', 'skill_id']);
            
            // Index
            $table->index(['skill_id']);
            $table->index(['education_level']);
            $table->index(['experience_level']);
            $table->index(['gender_preference']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('response_future_demand_skills');
    }
};