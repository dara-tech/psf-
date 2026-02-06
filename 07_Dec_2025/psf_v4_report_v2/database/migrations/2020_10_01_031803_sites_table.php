<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('username'); 
            $table->string('sitecode'); 
            $table->string('province'); 
            $table->string('name'); 
            $table->string('site');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
        });

        Schema::create('user_belong2_sites', function (Blueprint $table)  {
           
            
            $table->integer('site_id')->unsigned();
            $table->morphs('model');


            $table->foreign('site_id')
                ->references('id')
                ->on('sites')
                ->onDelete('cascade');

            $table->primary(['model_id', 'site_id','model_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        //
        Schema::dropIfExists('user_belong2_sites');
        Schema::dropIfExists('sites');
    }
}
