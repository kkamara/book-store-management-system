<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBook extends Model
{
    use HasFactory;
    
    protected $table = 'order_books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "order_id",
        "quantity",
        "isbn_13", // 9780545162074
        "isbn_10", // 0545162076
        "slug",
        "name",
        "description",
        "jpg_image_url",
        "cost", // Stored as integer by float value multiplied by 100.
        "rating_average",
        "binding", // Paperback, Hardcover
        // Note that the "e-dition" edition do not require an ISBN to be sold.
        "edition", // Bibliographical definition, Collectors' definition, Publisher definitions, Revised edition, Revised and updated edition, Co-edition, e-dition, Library edition, Book club edition, Cheap edition, Colonial edition, Cadet edition, Large print edition, Critical edition
        "author",
        "published",
        "publisher",
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function getFormattedCostAttribute() {
        return number_format(
            $this->cost / 100,
            2
        );
    }
}
