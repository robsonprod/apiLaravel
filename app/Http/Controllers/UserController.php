<?php namespace App\Http\Controllers;

use Auth;
use App\User;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');

		$rules = array(
			'email' => 'required',
			'password' => 'required'
			);

		$mssgs = array(
			'email.required' => 'Informe o email é necessário!',
			'password.required' => 'A senha não foi informada.'
			);

		$validator = Validator::make($credentials, $rules, $mssgs);

		if ($validator->fails()) {
			return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 200);
		} else {
			try {
				if (Auth::attempt($credentials, true)) {
					$user = Auth::user();
					return response()->json(['success' => true, 'user' => $user], 200);
				} else {
					return response()->json(['success' => false, 'errors' => ['Email ou senha inválido!']], 200);
				}
			} catch (\Exception $e) {
				return response()->json(['success' => false, 'errors' => [$e->getMessage()]], 200);
			}
		}
	}

	public function store(Request $request)
	{
		$inputs = $request->all();

		$rules = array(
			'email' => 'required|email|unique:users',
			'password' => 'required',
			// 'confirmed' => 'required|same:password',
			'name' => 'required'
			);

		$mssgs = array(
			'email.required' => 'Informe o email é obrigatório',
			'email.unique' => 'Email informado já está em uso',
			'email.email' => 'O email informado é invalido',
			'password.required' => 'Informe a senha',
			// 'confirmed.required' => 'Informe a confirmação de senha',
			// 'confirmed.same' => 'As senhas são diferentes',
			'name.required' => 'Informe o nome completo'
			);

		$validator = Validator::make($inputs, $rules, $mssgs);

		if ($validator->fails()) {
			return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 200);
		}		
		
		$inputs['password'] = bcrypt($inputs['password']);		
		$user = User::create($inputs);
		return response()->json(['success' => true, 'id' => $user->id], 200);
	}

	public function complete($status, $user)
	{
		if (empty($status) && empty($user)) {
			return response()->json(['error'], 400);
		}

		User::find($user)->update(['role' => $status]);
		return response()->json(['success' => true, 'status' => $status], 200);		
	}


	public function edit($id)
	{
		//
	}


	public function update($id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}

}