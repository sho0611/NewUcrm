<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTokensTable extends Migration
{
    public function up()
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained()->onDelete('cascade'); // adminとのリレーション
            $table->string('token')->unique()->comment('認証トークン');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // トークンの有効期限
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_tokens');
    }
}

