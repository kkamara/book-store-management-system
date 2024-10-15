<?php

namespace App\Enums\V1;

enum BookEdition: string {
    case BIBLIOGRAPHICAL = "Bibliographical definition";
    case COLLECTORS = "Collectors' definition";
    case PUBLISHER = "Publisher definition";
    case REVISED = "Revised edition";
    case REVISED = "Revised and updated edition";
    case CO_EDITION = "Co-edition";
    case E_DITION = "e-dition";
    case LIBRARY = "Library edition";
    case BOOK = "Book club edition";
    case CHEAP = "Cheap edition";
    case COLONIAL = "Colonial edition";
    case CADET = "Cadet edition";
    case LARGE = "Large print edition";
    case CRITICAL = "Critical edition";
}