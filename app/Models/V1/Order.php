<?php

namespace App\Models\V1;

use App\Enums\V1\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "reference_number",
        "cost",
        "delivery_cost",
        "status",
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "status" => OrderStatus::class,
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function orderBooks(): HasMany {
        return $this->hasMany(OrderBook::class);
    }

    public function getFormattedCostAttribute() {
        return number_format(
            $this->cost / 100,
            2
        );
    }

    public function getFormattedDeliveryCostAttribute() {
        return number_format(
            $this->delivery_cost / 100,
            2
        );
    }

    public function getFormattedTotalCostAttribute() {
        return number_format(
            ($this->cost + $this->delivery_cost) / 100,
            2
        );
    }
}
