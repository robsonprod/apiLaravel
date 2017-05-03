<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Crime extends Model {

	protected $fillable = ['latitude', 'longitude', 'data_crime', 'hora_crime', 'tipo_crime', 'tipo_local', 'tipo_meio', 'cidade_id'];
	protected $hidden = ['created_at', 'updated_at'];


	public function cidades()
	{
		$this->belongsTo('App\Cidade', 'cidade_id');
	}
}
