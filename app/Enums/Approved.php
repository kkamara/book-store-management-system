<?php

namespace App\Enums;

enum Approved: int {
    case APPROVED = 1;
    case NOT_JUDGED = 0;
    case DISAPPROVED = -1;
}