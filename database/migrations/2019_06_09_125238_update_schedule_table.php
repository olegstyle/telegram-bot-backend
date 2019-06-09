<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScheduleTable extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', static function (Blueprint $table) {
            $table->string('minutes')->nullable()->change();
            $table->string('hours')->nullable()->change();
            $table->string('day')->nullable()->change();
            $table->string('month')->nullable()->change();
            $table->string('week_day')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', static function (Blueprint $table) {
            $table->integer('minutes')->nullable()->change();
            $table->integer('hours')->nullable()->change();
            $table->integer('day')->nullable()->change();
            $table->integer('month')->nullable()->change();
            $table->integer('week_day')->nullable()->change();
        });
    }
}
