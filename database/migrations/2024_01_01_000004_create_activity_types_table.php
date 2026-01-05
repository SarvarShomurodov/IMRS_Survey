<?php
// database/migrations/2024_01_01_000004_create_activity_types_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz', 255);
            $table->string('name_ru', 255)->nullable();
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active']);
            $table->index(['code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_types');
    }
};
