<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloudflareZoneIdToDomainsTable extends Migration
{
    public function up()
    {
        Schema::table('domain', function (Blueprint $table) {
            $table->string('cloudflare_zone_id')->nullable()->after('cloudflare_account_id');
        });
    }

    public function down()
    {
        Schema::table('domain', function (Blueprint $table) {
            $table->dropColumn('cloudflare_zone_id');
        });
    }
}
