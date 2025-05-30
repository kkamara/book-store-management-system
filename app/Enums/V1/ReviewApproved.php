<?php

namespace App\Enums\V1;

enum ReviewApproved: int {
    case APPROVED = 1;
    case NOT_JUDGED = 0;
    case DISAPPROVED = -1;
}