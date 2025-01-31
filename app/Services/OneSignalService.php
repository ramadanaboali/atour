<?php

namespace App\Services;

use Ladumor\OneSignal\OneSignal;

class OneSignalService
{
    /**
     * Send a notification to a specific user
     */
    public static function sendToUser($playerId, $title, $message, $data = [])
    {
        $payload = [
            'include_player_ids' => [$playerId], // Send to a specific user
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data, // Custom payload (optional)
        ];

        return OneSignal::sendPush($payload);
    }

    /**
     * Send a notification to all users
     */
    public static function sendToAll($title, $message, $data = [])
    {
        $payload = [
            'included_segments' => ['All'], // Send to all users
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
        ];

        return OneSignal::sendPush($payload);
    }
}
