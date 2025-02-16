<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id',255) ;
            $table->string('website_url');
            $table->string('campaign_source');

            // $table->enum('campaign_source', ['facebook', 'google', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok']);
            $table->string('campaign_medium',255);
            $table->string('campaign_name',255);
            $table->longText('website_url_new');
            $table->longText('shorten_link')->nullable();
             $table->string('device_ip')->nullable();
            $table->string('visited_at')->nullable();
            $table->integer('visits')->default(0); // عدد الزيارات
            $table->integer('submissions')->default(0); // عدد النماذج المرسلة

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
        Schema::dropIfExists('campaigns');
    }
}
