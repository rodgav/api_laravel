<?php


namespace App\Helpers;


use App\Models\User;
use Firebase\JWT\JWT;

class JwtAuth
{
    public $key;

    /**
     * JwtAuth constructor.
     * @param $key
     */
    public function __construct()
    {
        $this->key = 'esta-es-una-clave-secreta';
    }


    public function singup($email, $password)
    {
        $user = User::query()->where(array('email' => $email, 'password' => $password))->first();
        if (is_object($user)) {
            //generar token y devolverlo
            $token = array('sub' => $user->id, 'email' => $user->email, 'name' => $user->name,
                'surname' => $user->surname, 'iat' => time(), 'exp' => time() + (7 * 24 * 60 * 60));
            return JWT::encode($token, $this->key, 'HS256');
        } else {
            //devolver un error
            return array('status' => 'error', 'message' => 'Login ha fallado');
        }
    }

    public function checkToken($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
            if (is_object($decoded) && isset($decoded->sub)) {
                return $decoded;
            } else {
                return null;
            }
        } catch (\UnexpectedValueException | \DomainException $e) {
            return null;
        }
    }
}
