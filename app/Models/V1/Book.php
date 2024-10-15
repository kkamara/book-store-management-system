<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "isbn_13", // 9780545162074
        "isbn_10", // 0545162076
        "user_id",
        "name",
        "description",
        "cost", // Stored as integer by float value multiplied by 100.
        "rating_average",
        "binding", // Paperback, Hardcover
        // Note that the "e-dition" edition do not require an ISBN to be sold.
        "edition", // Bibliographical definition, Collectors' definition, Publisher definitions, Revised edition, Revised and updated edition, Co-edition, e-dition, Library edition, Book club edition, Cheap edition, Colonial edition, Cadet edition, Large print edition, Critical edition
        "author",
        "published",
        "publisher",
        "approved",
    ];
}
