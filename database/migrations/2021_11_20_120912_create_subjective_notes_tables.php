<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectiveNotesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createSubjectTable();
        $this->createNoteTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->deleteNoteTable();
        $this->deleteSubjectTable();
    }

    /**Create tables**********************/
    private function createSubjectTable(){
        Schema::create('subject', function (Blueprint $table){
			$table->bigIncrements('id');
            $table->bigInteger('id_user')->unsigned()->nullable();
			$table->string('name', 100);
			$table->string('description', 300);
			$table->boolean('is_deleted')->default(0);
			$table->timestamps();
   
			$table->foreign('id_user')
			->references('id')
			->on('users')
			->onDelete('cascade');
		});   
    }

    private function createNoteTable(){
        Schema::create('subject_note', function (Blueprint $table){
			$table->bigIncrements('id');
            $table->bigInteger('id_subject')->unsigned()->nullable();
			$table->text('note');
			$table->boolean('is_deleted')->default(0);
			$table->timestamps();
   
			$table->foreign('id_subject')
			->references('id')
			->on('subject')
			->onDelete('cascade');
		});  
    }

    /**Delete tables**********************/
    private function deleteSubjectTable(){
        Schema::table('subject', function (Blueprint $table) {
            $table->dropForeign('subject_id_user_foreign');
        });

        Schema::dropIfExists('subject'); 
    }

    private function deleteNoteTable(){
        Schema::table('subject_note', function (Blueprint $table) {
            $table->dropForeign('subject_note_id_subject_foreign');
        });

        Schema::dropIfExists('subject_note'); 
    }
}
