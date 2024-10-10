<?php

use App\Broadcasting\ClientChannel;

Broadcast::channel('client.presence.{client}', ClientChannel::class);
