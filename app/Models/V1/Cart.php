<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "book_id",
        "user_id",
        "quantity",
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo {
        return $this->belongsTo(Book::class);
    }

    public function getCostAttribute() {
        $cost = $this->book->cost;
        $cost *= $this->quantity;
        return number_format(
            $cost / 100,
            2,
        );
    }
}
