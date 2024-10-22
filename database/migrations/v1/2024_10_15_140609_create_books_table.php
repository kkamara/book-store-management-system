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
            $table->unsignedBigInteger("user_id");
            $table->string("isbn_13")
                ->index()
                ->nullable();
            $table->string("isbn_10")
                ->index()
                ->nullable();
            $table->string("name")
                ->index();
            $table->text("description");
            $table->text("jpg_image_url")->nullable();
            $table->integer("cost")->default(0);
            $table->float("rating_average")->nullable();
            $table->enum("binding", ["Hardcover", "Paperback"]);
            $table->string("edition");
            $table->string("author");
            $table->date("published");
            $table->string("publisher")
                ->index();
            $table->tinyInteger("approved");
            $table->timestamps();
            
            $table->foreign("user_id")
                ->references("id")
                ->on("users");
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
