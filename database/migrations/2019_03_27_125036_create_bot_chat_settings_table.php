<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotChatSettingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('bot_chat_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('bot_chat_id');
            $table->foreign('bot_chat_id')->references('id')->on('bot_chats');

            $table->string('setting');
            $table->text('value')->nullable();

            $table->unique(['bot_chat_id', 'setting']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_chat_settings');
    }
}
