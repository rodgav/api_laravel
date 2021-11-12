<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            $cars = Car::all()->load('user');
            return response()->json(array('cars' => $cars, 'status' => 'success'), 200);
        } else {
            return response()->json(array('cars' => null, 'status' => 'error'), 200);
        }
    }

    public function show(Request $request, $id)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            $car = Car::query()->find($id)->load('user');
            return response()->json(array('car' => $car, 'status' => 'success'), 200);
        } else {
            return response()->json(array('car' => null, 'status' => 'error'), 200);
        }
    }

    public function store(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (!is_null($checkToken)) {
            //recoger datos por POST
            $json = $request->input('json', null);
            $params = json_decode($json);

            //asignar usuario
            $user = $checkToken;

            if (isset($params->title) && isset($params->description)
                && isset($params->price) && isset($params->status)) {
                //guardar el carro
                $car = new Car();
                $car->id_user = $user->sub;
                $car->title = $params->title;
                $car->description = $params->description;
                $car->price = $params->price;
                $car->status = $params->status;
                $car->save();

                $data = array('car' => $car, 'status' => 'success', 'code' => 200, 'message' => 'Auto creado correctamente');
            } else {
                $data = array('car' => null, 'status' => 'error', 'code' => 400, 'message' => 'Faltan datos');
            }

        } else {
            $data = array('car' => null, 'status' => 'error', 'code' => 400, 'message' => 'Token incorrecto');
        }
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (!is_null($checkToken)) {
            //recoger datos por POST
            $json = $request->input('json', null);
            $params = json_decode($json);

            //asignar usuario
            $user = $checkToken;

            if (isset($params->title) && isset($params->description)
                && isset($params->price) && isset($params->status)) {
                //actualizo carro
                $car = Car::query()->where('id', $id)->update([
                    'title' => $params->title,
                    'description' => $params->description,
                    'price' => $params->price,
                    'status' => $params->status,
                ]);

                $data = array('car' => $car, 'status' => 'success', 'code' => 200, 'message' => 'Auto actualizado correctamente');
            } else {
                $data = array('car' => null, 'status' => 'error', 'code' => 400, 'message' => 'Faltan datos');
            }

        } else {
            $data = array('car' => null, 'status' => 'error', 'code' => 400, 'message' => 'Token incorrecto');
        }
        return response()->json($data, 200);
    }

    public function destroy(Request $request, $id)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (!is_null($checkToken)) {

            //elimino carro
            $car = Car::query()->find($id);
            if (is_object($car)) {
                $car->delete();

                $data = array('car' => $car, 'status' => 'success', 'code' => 200, 'message' => 'Auto eliminado correctamente');
            } else {
                $data = array('car' => $car, 'status' => 'success', 'code' => 200, 'message' => 'El auto no se encontro');

            }

        } else {
            $data = array('car' => null, 'status' => 'error', 'code' => 400, 'message' => 'Token incorrecto');
        }
        return response()->json($data, 200);
    }
}
