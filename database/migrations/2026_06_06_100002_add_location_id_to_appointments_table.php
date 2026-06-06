<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedInteger('location_id')->nullable()->after('employee_id');
            $table->foreign('location_id', 'location_fk_appointment')->references('id')->on('locations');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('location_fk_appointment');
            $table->dropColumn('location_id');
        });
    }
}
