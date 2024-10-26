<?php

namespace App\Enums\V1;

enum OrderStatus: string {
    case PROCESSING = "PROCESSING";
    case PROCESSED = "PROCESSED";
    case DELIVERING = "DELIVERING";
    case DELIVERED = "DELIVERED";
}