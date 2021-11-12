<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //recoger post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role = 'ROLE_USER';
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        if (!is_null($email) && !is_null($name) && !is_null($surname) && !is_null($password)) {
            //crear usuario
            $user = new User();
            $user->email = $email;
            $user->password = $password;
            $user->name = $name;
            $user->surname = $surname;
            $user->role = $role;

            //comprobar duplicado
            $isset_user = User::query()->where('email', '=', $email)->first();

            if (is_null($isset_user)) {
                //guardar usuario
                $user->save();
                $data = array('status' => 'success', 'code' => 200, 'message' => 'Usuario registrado correctamente');
            } else {
                //no guardar ya existe;
                $data = array('status' => 'success', 'code' => 400, 'message' => 'Usuario duplicado');
            }
        } else {
            $data = array('status' => 'error', 'code' => 400, 'message' => 'Usuario no creado');
        }
        return response()->json($data, 200);
    }

    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();
        // recibir post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        if (!is_null($email) && !is_null($password)) {
            $signup = $jwtAuth->singup($email, $password);
            return response()->json($signup, 200);
        }
        return response()->json(array('status' => 'error', 'message' => 'Envia tus datos por post'));
    }
}
