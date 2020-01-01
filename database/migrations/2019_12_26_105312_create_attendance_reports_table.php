<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_reports', function (Blueprint $table) {
						$table->increments('id');
						$table->integer('schedule_id');
						$table->integer('guru_pengganti')->nullable();
						$table->string('semester');
						$table->time('jam_masuk')->nullable();
						$table->time('jam_keluar')->nullable();
						$table->integer('pertemuan');
						$table->date('tgl_pertemuan');
						$table->text('pokok_bahasan');
						$table->text('tugas')->nullable();
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
        Schema::dropIfExists('attendance_reports');
    }
}
