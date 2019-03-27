<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotEventsToChatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('bot_events_to_chats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('bot_chat_id');
            $table->foreign('bot_chat_id')->references('id')->on('bot_chats');

            $table->unsignedBigInteger('bot_event_id');
            $table->foreign('bot_event_id')->references('id')->on('bot_events');

            $table->unique(['bot_chat_id', 'bot_event_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_events_to_chats');
    }
}
