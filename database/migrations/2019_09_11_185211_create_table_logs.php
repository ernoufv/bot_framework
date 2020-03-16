<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogs extends Migration
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime("datetime")->useCurrent();
            $table->string("user_id");
            $table->string("channel")->nullable($value = true);
            $table->string("sender");
            $table->string("type");
            $table->string("text")->nullable($value = true);
            $table->text("payload")->nullable($value = true);

            $table->json("message")->nullable($value = true);

            $table->string("intent")->nullable($value = true);

            $table->string("fb_mid")->nullable($value = true);
            $table->boolean("fb_echo")->default($value = false);
            $table->dateTime("fb_echo_date")->nullable($value = true);
            $table->boolean("fb_delivered")->default($value = false);
            $table->dateTime("fb_delivered_date")->nullable($value = true);
            $table->boolean("fb_readed")->default($value = false);
            $table->dateTime("fb_readed_date")->nullable($value = true);

            $table->index('user_id');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('logs');
    }
}
