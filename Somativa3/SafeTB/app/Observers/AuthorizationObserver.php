<?php

namespace App\Observers;

use App\Models\Authorization;
use App\Services\AuthorizationNotificationService;

class AuthorizationObserver
{
    public function created(Authorization $authorization): void
    {
        app(AuthorizationNotificationService::class)->sendCreated($authorization);
    }
}
