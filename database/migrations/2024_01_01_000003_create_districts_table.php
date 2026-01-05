<?php
// database/migrations/2024_01_01_000003_create_districts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id'); // Foreign key ustuni
            $table->string('name_uz', 100);
            $table->string('name_ru', 100)->nullable();
            $table->string('code', 15)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            
            // Indekslar
            $table->index(['region_id', 'is_active']);
            $table->index(['code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('districts');
    }
};