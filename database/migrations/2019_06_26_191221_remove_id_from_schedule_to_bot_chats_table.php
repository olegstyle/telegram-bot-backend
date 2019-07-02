<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIdFromScheduleToBotChatsTable extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_to_bot_chats', static function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropColumn(['id']);
        });
    }

    public function down(): void
    {
        Schema::table('schedule_to_bot_chats', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
    }
}
