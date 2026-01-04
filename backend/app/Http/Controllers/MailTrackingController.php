<?php

namespace App\Http\Controllers;

use App\Models\MailDelivery;
use Illuminate\Http\Response;

class MailTrackingController extends Controller
{
    /**
     * トラッキングピクセル（1x1透明GIF）を返し、開封を記録
     */
    public function pixel(string $token): Response
    {
        // トークンでメール配信レコードを検索
        $delivery = MailDelivery::where('tracking_token', $token)->first();

        if ($delivery) {
            $delivery->recordOpen();
        }

        // 1x1 透明GIFを返す
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($gif, 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => strlen($gif),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT',
        ]);
    }
}
