<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotEventsTable extends Migration
{
    public function up(): void
    {
        Schema::create('bot_events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('event');
            $table->text('message');
            $table->string('photo_path')->nullable();
            $table->boolean('active')->default(true);

            $table->index(['user_id', 'event']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_events');
    }
}
