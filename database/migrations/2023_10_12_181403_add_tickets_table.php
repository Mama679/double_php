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
        Schema::table('tickets',function(Blueprint $table){
            $table->after('nota',function($table){
                $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign('usuarios_usuario_id_foreign');
    }
};
