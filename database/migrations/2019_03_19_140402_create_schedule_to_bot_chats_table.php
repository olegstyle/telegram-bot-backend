<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleToBotChatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_to_bot_chats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->unsignedBigInteger('bot_chat_id');
            $table->foreign('bot_chat_id')->references('id')->on('bot_chats');

            $table->unique(['schedule_id', 'bot_chat_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_to_bot_chats');
    }
}
