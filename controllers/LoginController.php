<?php 

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController {

    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $auth->validarLogin();

            if(empty($alertas)) {
                //verificar que el usuario existe
                $usuario = Usuario::where('email', $auth->email);
                if(!$usuario || !$usuario->confirmado)  {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                } else {
                    //el usuario existe, hay que comprobar el password
                    if(password_verify($auth->password, $usuario->password)) {
                        //iniciar sesion
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //redireccionar
                        header('Location: /dashboard');
                    } else {
                        usuario::setAlerta('error', 'El email o la contraseña no son correctos');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }


    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }


    public static function crear(Router $router) {
        $usuario = new Usuario();
        $alertas = []; 

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                    if($existeUsuario) {
                        Usuario::setAlerta('error', 'El usuario ya existe');
                        $alertas = Usuario::getAlertas();
                    } else {
                        //crear un nuevo usuario
                        $usuario->hashPassword();

                        //eliminar password2
                        unset($usuario->password2);

                        //generar un token unico
                        $usuario->generarToken();

                        //enviar mail de confirmacion
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();

                        //crear el usuario
                        $resultado = $usuario->guardar();

                        if($resultado) {
                            header('Location: /mensaje');
                        }            
                    }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                //buscar al usuario
                $usuario = Usuario::where('email', $usuario->email);

                //si lo encuentra
                if($usuario && $usuario->confirmado === '1') {
                    //generar un nuevo token
                    $usuario->generarToken();
                    unset($usuario->password2);
                    //actualizar el usuario
                    $usuario->guardar();
                    //enviar el mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->recuperarPassword();
                    //imprimir alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu e-mail');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
        }


        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Recuperar password',
            'alertas' => $alertas
        ]);
    }


    public static function reestablecer(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        if(!$token) {
            header('Location: /');
        }
        $error = false;

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            $alertas = Usuario::setAlerta('error', 'El usuario no existe');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                $usuario->hashPassword();
                $usuario->token = '';
                $resultado = $usuario->guardar();
            
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer contraseña',
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    
    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', [
            'titulo' => 'Confirmar cuenta'
        ]);
    }

    
    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        if(!$token) {
            header('Location: /');
        }

        //Encontrar al usuario con el token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            //no se encontró un usuario con ese token
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            //confirmar la cuenta
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token= '';
            
            //guardar el usuario confirmado
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Tu cuenta se ha confirmado correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta confirmada',
            'alertas' => $alertas
        ]);
    }
}