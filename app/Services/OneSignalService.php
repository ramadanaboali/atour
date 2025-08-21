<?php

namespace App\Services;

use Ladumor\OneSignal\OneSignal;
use App\Models\Notification;
use App\Models\PlayerId;
use App\Models\User;

class OneSignalService
{
    public static function sendToUser($userId, $title, $message)
    {
        $payload = [
            'include_player_ids' => [self::getPlayerId($userId)],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ];

        $response = OneSignal::sendPush($payload,$message);

        // Store in the database
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false
        ]);

        return $response;
    }
    public static function sendToAll( $title, $message)
    {
        $users = User::get();
        foreach($users as $user){
            $payload = [
                'include_player_ids' => [self::getPlayerId($user->id)],
                'headings' => ['en' => $title],
                'contents' => ['en' => $message],
            ];
            
            $response = OneSignal::sendPush($payload,$message);
        }

      
    }

    private static function getPlayerId($userId)
    {
        return PlayerId::where('user_id', $userId)->pluck('player_id')->toArray();
    }
}
