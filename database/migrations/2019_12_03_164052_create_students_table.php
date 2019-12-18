<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
						$table->increments('id');
						$table->integer('group_id');
            $table->string('nis');
						$table->string('name');
						$table->string('jenis_kelamin');
            $table->year('tahun_masuk');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('no_hp_ortu');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('photo');
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
        Schema::dropIfExists('students');
    }
}
