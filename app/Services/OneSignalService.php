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
        $playerIds = self::getPlayerIds($userId);
        if (empty($playerIds)) {
            return null;
        }
        $payload = [
            'include_player_ids' => $playerIds,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ];

        $response = OneSignal::sendPush($payload, $message);

        // Store in the database
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false
        ]);

        return $response;
    }
    public static function sendToAll($title, $message)
    {
        $users = User::get();
        foreach ($users as $user) {
            $playerIds = self::getPlayerIds($user->id);
            if (empty($playerIds)) {
                continue;
            }
            $payload = [
                'include_player_ids' => $playerIds,
                'headings' => ['en' => $title],
                'contents' => ['en' => $message],
            ];
            OneSignal::sendPush($payload, $message);
        }
    }

    private static function getPlayerIds($userId)
    {
        return PlayerId::where('user_id', $userId)->pluck('player_id')->filter()->values()->all();
    }
}
