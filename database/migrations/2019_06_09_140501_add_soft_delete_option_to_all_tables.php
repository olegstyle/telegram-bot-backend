<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteOptionToAllTables extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('schedule_actions', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('bots', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('bot_chats', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('bot_chat_settings', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('bot_events', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('posts', static function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('users', static function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('schedule_actions', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('bots', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('bot_chats', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('bot_chat_settings', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('bot_events', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('posts', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('users', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
