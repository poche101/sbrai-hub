<?php

namespace App\Services;

class AgoraTokenService
{
    public static function generateToken(string $channelName, int $uid): string
    {
        $appId  = config('services.agora.app_id');
        $cert   = config('services.agora.app_certificate');
        $expire = time() + 3600;

        // Requires composer package: agoraio-community/agora-token
        return \AgoraIO\Media\RtcTokenBuilder::buildTokenWithUid(
            $appId,
            $cert,
            $channelName,
            $uid,
            \AgoraIO\Media\RtcTokenBuilder::RoleAttendee,
            $expire
        );
    }
}
