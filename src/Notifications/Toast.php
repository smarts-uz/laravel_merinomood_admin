<?php

namespace Arniro\Admin\Notifications;

use Illuminate\Contracts\Support\Responsable;

class Toast implements Responsable
{
    private $message;
    private $type;

    private function __construct($message, $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public static function info($message)
    {
        return new static($message, 'info');
    }

    public static function success($message)
    {
        return new static($message, 'success');
    }

    public static function danger($message)
    {
        return new static($message, 'error');
    }

    public function toResponse($request)
    {
        return response()->json([
           'toast' => [
               'type' => $this->type,
               'message' => $this->message
           ]
        ]);
    }
}
