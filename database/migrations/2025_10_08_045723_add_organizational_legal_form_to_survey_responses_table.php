<?php
// database/migrations/2025_01_15_000001_add_organizational_legal_form_to_survey_responses.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->enum('organizational_legal_form', ['davlat', 'xususiy'])
                  ->after('employee_count')
                  ->nullable()
                  ->comment('Tashkiliy-huquqiy shakl');
        });
    }

    public function down()
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn('organizational_legal_form');
        });
    }
};