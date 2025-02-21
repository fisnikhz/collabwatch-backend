<?php

namespace App\Enums;

enum FriendStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case BLOCKED = 'blocked';
    case REJECTED = 'rejected';
}
