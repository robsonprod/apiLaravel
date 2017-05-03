<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('crimes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('cidade_id')->unsigned();
			$table->double('latitude');
			$table->double('longitude');
			$table->date('data_crime');
			$table->time('hora_crime');	
			$table->enum('tipo_crime', [
				'VIOLENCIA_DOMESTICA_FAMILIAR',
				'FEMINICIDIO',
				'VIOLENCIA_SEXUAL',
				'VIOLENCIA_DE_GENERO_NA_INTERNET',
				'VIOLENCIA_RACISMO',
				'VIOLENCIA_LESBICAS'
				]);
			$table->enum('tipo_local', [
				'ESCOLA',
				'ESTABELECIM_COMERCIAL',
				'OUTROS',
				'PRACA_PUBLICA',
				'RESIDENCIA',
				'TRANSPORTE_COLETIVO',
				'VIA_PUBLICA'
				]);
			$table->enum('tipo_meio', [
				'ARMA_BRANCA',
				'ARMA_DE_FOGO',
				'ENFORCAMENTO_SUFOCACAO',
				'QUEIMADURAS',
				'OUTROS',
				'OBJETO_CONTUNDENTE',
				'FORCA_CORPORAL'
				]);
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
		Schema::drop('crimes');
	}

}
