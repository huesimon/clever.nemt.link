<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use NotificationChannels\Telegram\TelegramMessage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('locations/{location:external_id}', [LocationController::class, 'show'])->name('locations.show');


Route::post('telegram/webhook', function () {
    Log::info('Telegram webhook called', request()->all());

    $chatId = request()->input('message')['chat']['id'];
    $messageText = request()->input('message')['text'];
    $isTopicMessage = request()->input('message')['is_topic_message'] ?? false;

    $options = $isTopicMessage ? ['message_thread_id' =>  request()->input('message')['message_thread_id']] : [];


    


    TelegramMessage::create()
        ->content($messageText)
        ->to($chatId)
        ->options($options)
        ->send();
    ///
    ///


    return 'ok';


    $botApiKey = env('TELEGRAM_BOT_TOKEN');
    $botUserName = env('TELEGRAM_BOT_USERNAME');

    try {
        $telegram = new Longman\TelegramBot\Telegram($botApiKey, $botUserName);

        // Handle telegram webhook request
        $telegram->handle();
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }

    // return what the user sent
    return response()->json([
        'the_message_from_user' => $telegram->getCustomInput(),
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
