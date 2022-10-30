<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asay_chattings', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('sender_id');
            $table->string('receivers');
            $table->longText('message');
            $table->string('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asay_chattings');
    }
};
