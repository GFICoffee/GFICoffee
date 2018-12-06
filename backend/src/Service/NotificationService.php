<?php

namespace App\Service;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class NotificationService
{
    /** @var Subscription */
    private $subscription;

    /** @var WebPush */
    private $webPush;

    public function __construct(WebPush $webPush)
    {
        $destination = json_decode(
            '{"endpoint":"https://fcm.googleapis.com/fcm/send/fW-bN0jGFJY:APA91bFtBkvgUhgJGyHtDhpq3GOSSWHROmKu1NzHTOls6jCzWMT1wBxyfMJ9iSX0pzi_kTu7-jeWx-nglTEhlV3EPLkFIMNF8-hjgWBOINGeNkY4WpCCRUKFz6AAohQZLGFPA61oBwGj","expirationTime":null,"keys":{"p256dh":"BLAMBAClYrjoujiNxED9VOkKg_frv7gvcyhy4wEK72nK6msOZlNz9CQFpCjRsfjzi1lWnrU1zyK71NGDNCA3Y-4","auth":"Q-0Cu5fMGykg3b7NPK_mZw"}}',
            true);
        $destination['contentEncoding'] = 'aesgcm';

        $this->subscription = Subscription::create($destination);
        $this->webPush = $webPush;
    }

    public function sendNotification(string $message)
    {
        return $this->webPush->sendNotification(
            $this->subscription,
            '{"msg":"MY MSG"}',
            true
        );
    }
}