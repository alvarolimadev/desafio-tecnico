<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressUserTable extends Migration {
    public function up() {
        Schema::create('address_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'address_id']); // evita duplicidade
        });
    }
    public function down() {
        Schema::dropIfExists('address_user');
    }
}