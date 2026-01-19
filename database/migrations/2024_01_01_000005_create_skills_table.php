<?php
// database/migrations/2024_01_01_000005_create_skills_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence_number');
            $table->string('group_code', 20);
            $table->text('name');
            $table->string('name_normalized', 500)->nullable()->index(); // qidiruv uchun
            $table->enum('worker_type', ['xizmatchi', 'ishchi']);
            $table->string('qualification_category', 10)->nullable();
            $table->string('skill_grade_range', 50)->nullable();
            $table->integer('national_qualification_level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'worker_type']);
            $table->index(['sequence_number']);
            $table->index(['group_code']);
            
            // Full-text search uchun (ixtiyoriy)
            // $table->fullText(['name', 'name_normalized']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('skills');
    }
};