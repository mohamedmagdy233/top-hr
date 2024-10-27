<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait FirebaseNotification
{

    public function sendFcm($data, $user_id = null)
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/topbusiness-2b08d/messages:send';
        $accessToken = $this->getAccessToken();

        if ($user_id) {
            $deviceTokens = [User::whereId($user_id)->first()->fcm_token];
        } else {
            $deviceTokens = User::pluck('fcm_token')->toArray();
        }

        Notification::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'user_id' => $user_id,

        ]);

        $responses = [];
        foreach ($deviceTokens as $token) {
            $payload = $this->preparePayload($data, $token);
            $responses[] = $this->sendNotification($apiUrl, $accessToken, $payload);
        }

        return response()->json(['responses' => $responses]);
    }

    // edit
    protected function getAccessToken()
    {
        $credentialsFilePath = storage_path('app/topbusiness.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }

    protected function preparePayload($data, $token)
    {

        $message = [
            'notification' => [
                'title' => $data['title'],
                'body' => $data['body'],
            ],
            'token' => $token
        ];

        return json_encode(['message' => $message]);
    }

    protected function sendNotification($url, $accessToken, $payload)
    {
        $headers = [
            "Authorization: Bearer " . $accessToken,
            'Content-Type: application/json'
        ];



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => $error_msg];
        }

        $info = curl_getinfo($ch);
        curl_close($ch);

        return ['response' => json_decode($response, true), 'info' => $info];
    }
}
