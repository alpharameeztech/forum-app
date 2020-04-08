<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumAppearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_appearances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('forum_theme_id')->nullable();
            $table->string('primary_button_color')->nullable();
            $table->string('link_color')->nullable();
            $table->string('heading_color')->nullable();
            $table->string('paragraph_color')->nullable();
            $table->text('css_code')->nullable();
            $table->text('js_code')->nullable();
            $table->unsignedSmallInteger('paragraph_font_size')->nullable();
            $table->unsignedSmallInteger('heading_font_size')->nullable();
            $table->string('banner')->nullable();
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
        Schema::dropIfExists('forum_appearances');
    }
}
