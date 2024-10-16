<?php

use App\Models\V1\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger(column: "book_id");
            $table->enum("rating", [0, 1, 2, 3, 4, 5]);
            $table->text("text")
                ->default(null)
                ->nullable();
            $table->tinyInteger("approved")->default(0);
            $table->timestamps();
            
            $table->foreign("user_id")
                ->references("id")
                ->on("users");
            $table->foreign("book_id")
                ->references("id")
                ->on("books");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
