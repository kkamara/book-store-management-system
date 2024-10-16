<?php

namespace App\Models\V1;

use App\Enums\V1\BookApproved;
use App\Enums\V1\BookEdition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "edition" => BookEdition::class,
            "approved" => BookApproved::class,
        ];
    }

    /**
     * Get the user that owns the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviews for the book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The categories that belong to the book.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, "book_categories");
    }
}
