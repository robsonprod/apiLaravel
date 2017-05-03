<?php namespace App\Http\Controllers;

use Input;
use App\Crime;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Illuminate\Http\Request;

class CrimeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getResultChart(){
		try {
			$modelCrimes = Crime::all();
			$crimes = array();

			foreach ($modelCrimes as $crime) {
				$obj = new \StdClass();

				if (count($crimes) == 0) {
					$obj->key = $crime->tipo_crime;
					$obj->y = 1;
					$crimes[] = $obj;
				} else {
					$isIncluir = true;

					foreach ($crimes as $element) {
						if ($element->key == $crime->tipo_crime) {
							$element->y += 1;		
							$isIncluir = false;
							break;
						}		
					}

					if ($isIncluir) {
						$obj->key = $crime->tipo_crime;
						$obj->y = 1;
						$crimes[] = $obj;
					}

				}
			}

			return response()->json($crimes ,200); 	
		} catch (\Exception $e) {
			return response()->json($e->getMessage() ,200); 	
		}
	}
	public function index()
	{
		try {
			$crimes = Crime::all();
			return response()->json($crimes, 200);	
		} catch (\Exception $e) {
			return response()->json(['msg' => $e->getMessage()]);
		}

	}

	public function store(Request $request)
	{

		try {
			$inputs = $request->all();

			$rules = array(
				'data_crime' => 'required|date',
				'hora_crime' => 'required',
				'tipo_crime' => 'required',
				'tipo_local' => 'required',
				'tipo_meio' => 'required'
				);

			$mssgs = array(
				'data_crime.required' => 'Data do crime é obrigatório',
				'data_crime.date' => 'Data do crime informado é invalido.',
				'hora_crime.required' => 'Hora do crime é obrigatório.',
				'tipo_crime.required' => 'Tipo de crime é obrigatório.',
				'tipo_local.required' => 'Local do crime é obrigatório.',
				'tipo_meio.required' => 'Meio usado no crime é obrigatório.'
				);

			$validator = Validator::make($inputs, $rules, $mssgs);

			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 200);
			}		

			$crime = Crime::create($inputs);

			if (!empty($crime)) {
				return response()->json(['success' => true], 200);
			}

			return response()->json(['success' => false], 200);			
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()], 200);
		}

	}

	public function filtro($cidade, $tipo) {
		try {

			$crimes = null;
			
			if ($tipo == 'all') {
				$modelCrimes = Crime::where(['cidade_id' => $cidade])->get();
			} else {
				$modelCrimes = Crime::where(['tipo_crime' => $tipo, 'cidade_id' => $cidade])->get();
			}


			if (!$modelCrimes->isEmpty()) {

				foreach ($modelCrimes as $modelCrime) {
					$obj = new \StdClass();
					$obj->local = new \StdClass();
					$obj->local->crimes = array();

					$sobreCrime = [
						'data_crime' => $modelCrime->data_crime,
						'hora_crime' => $modelCrime->hora_crime,
						'tipo_crime' => $modelCrime->tipo_crime,
						'tipo_local' => $modelCrime->tipo_local,
						'tipo_meio' => $modelCrime->tipo_meio
					];

					if (empty($crimes)) {
						$crimes = array();

						$obj->local->latitude = $modelCrime->latitude;	
						$obj->local->longitude = $modelCrime->longitude;
						$obj->local->crimes[] = $sobreCrime;
						$crimes[] = $obj;						
					} else {

						foreach ($crimes as $key => $crime) {
							if ($crime->local->latitude == $modelCrime->latitude && 
									$crime->local->longitude == $modelCrime->longitude) {

								$crime->local->crimes[] = $sobreCrime;
								break;
							}

							if ($key == sizeof($crimes) - 1) {
								$obj->local->latitude = $modelCrime->latitude;
								$obj->local->longitude = $modelCrime->longitude;
								$obj->local->crimes[] = $sobreCrime;
								$crimes[] = $obj;
							}
						}					
				}

			}
		}

		return response()->json($crimes ,200); 	
	} catch (\Exception $e) {
		return response()->json($e->getMessage() ,200); 	
	}
}

}
