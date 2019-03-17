<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotChatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('bot_chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bot_id');
            $table->foreign('bot_id')->references('id')->on('bots');
            $table->string('label');
            $table->string('chat_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_chats');
    }
}
