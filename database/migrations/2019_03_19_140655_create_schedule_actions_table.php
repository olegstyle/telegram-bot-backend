<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleActionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_actions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedules');

            $table->string('action');
            $table->unsignedBigInteger('action_id');

            $table->index(['action']);
            $table->index(['action_id']);
            $table->unique(['schedule_id', 'action', 'action_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_actions');
    }
}
