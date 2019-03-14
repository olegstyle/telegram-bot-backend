<?php declare(strict_types=1);

use App\Models\Bot;
use App\Models\BotChat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->create([
            'name' => 'Tester',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $bot = new Bot();
        $bot->user()->associate($user);
        $bot->label = 'Test Bot';
        $bot->token = '648333641:AAFiGDFChyQGYnp-7BtBEeMz7OCxClbQ4rk';
        $bot->save();

        $botChat = new BotChat();
        $botChat->bot()->associate($bot);
        $botChat->label = 'Test Bot Chat';
        $botChat->chat_id = 'bot_testing_group';
        $botChat->save();
    }
}
