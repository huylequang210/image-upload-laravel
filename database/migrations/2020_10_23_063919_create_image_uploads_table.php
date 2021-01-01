<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('original');
            $table->string('thumbnail');
            $table->unsignedInteger('user_id')->index();
            $table->string('title', '100')->default('No title');
            $table->unsignedInteger('public_status')->default(1);
            $table->unsignedBigInteger('view')->default(1);
            $table->unsignedBigInteger('upvote')->default(0);
            $table->float('original_data');
            $table->float('thumbnail_data');
            $table->softDeletes();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_uploads');
    }
}
