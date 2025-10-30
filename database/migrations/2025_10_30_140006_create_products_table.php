<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'products', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name', 50 );
            $table->string( 'price', 50 );
            $table->string( 'unit', 50 );

            $table->unsignedBigInteger( 'user_id' );
            $table->unsignedBigInteger( 'category_id' );
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' )->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'products' );
    }
};