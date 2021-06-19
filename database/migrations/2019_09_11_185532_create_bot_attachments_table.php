<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bot_url')->nullable($value = true)->default($value = null);
            $table->string('attachment_id')->nullable($value = true)->default($value = null);
            $table->string('type')->nullable($value = true)->default($value = null);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_bin';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_attachments');
    }
}