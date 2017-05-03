<?php

Route::group(['middleware' => 'cors', 'prefix' => 'api/v1'], function(){
	Route::group(['prefix' => 'crimes'], function(){
		Route::get('', 'CrimeController@index');
		Route::get('resultado/charts', 'CrimeController@getResultChart');
		Route::get('cidade/{cidade}/tipo/{tipo}', 'CrimeController@filtro');
		Route::post('', 'CrimeController@store');
	});

	Route::post('login', 'UserController@login');
	Route::post('store', 'UserController@store');

	Route::get('cidades/{uf}', function($uf) {
		$cidadeModel = App\Cidade::where('estado_id', $uf)->get();
		$cidades = array();

		if (!$cidadeModel->isEmpty()) {
			foreach ($cidadeModel as $cidade) {
				$obj = new \stdClass();
				$obj->id = $cidade->id;
				$obj->nome = $cidade->nome;
				$cidades[] = $obj;
			}
		}

		return response()->json(['cidades' => $cidades], 200);
	});

	Route::get('estados', function() {
		$modelEstado = App\Estado::get();
		$estados = array();

		if (!$modelEstado->isEmpty()) {
			foreach ($modelEstado as $estado) {
				$obj = new \stdClass();
				$obj->id = $estado->id;
				$obj->uf = $estado->nome;
				$estados[] = $obj;
			}
		}

		return response()->json(['estados' => $estados], 200);
	});
});