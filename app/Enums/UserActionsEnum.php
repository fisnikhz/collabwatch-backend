<?php

namespace App\Enums;

enum UserActionsEnum : int
{
    case LIKE_SCORE = 5;
    case SAVE_SCORE = 10;
    case COMMENT_SCORE = 20;

}
