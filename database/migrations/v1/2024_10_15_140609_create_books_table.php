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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId(User::class);
            $table->integer("isbn_13")->nullable();
            $table->integer("isbn_10")->nullable();
            $table->string("name");
            $table->text("description");
            $table->integer("cost")->default(0);
            $table->float("rating_average");
            $table->enum("binding", ["Hardcover", "Paperback"]);
            $table->string("edition");
            $table->string("author");
            $table->string("published");
            $table->date("publisher");
            $table->tinyInteger("approved");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
