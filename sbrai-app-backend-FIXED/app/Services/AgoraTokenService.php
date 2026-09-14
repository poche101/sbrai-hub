<?php

namespace App\Services;

use TaylanUnutmaz\AgoraTokenBuilder\RtcTokenBuilder;

class AgoraTokenService
{
    public static function generateToken(string $channelName, int $uid): string
    {
        $appId  = config('services.agora.app_id');
        $cert   = config('services.agora.app_certificate');
        $expire = time() + 3600;

        // Package: composer require taylanunutmaz/agora-token-builder
        // (the "agoraio-community/agora-token" package referenced in an
        // earlier version of this file does not exist on Packagist —
        // this is the real, published equivalent, using the same
        // buildTokenWithUid() signature. Role_Attendee isn't offered by
        // this package; Role_Publisher is the correct equivalent for a
        // user who can both send and receive audio/video.)
        return RtcTokenBuilder::buildTokenWithUid(
            $appId,
            $cert,
            $channelName,
            $uid,
            RtcTokenBuilder::RolePublisher,
            $expire
        );
    }
}
