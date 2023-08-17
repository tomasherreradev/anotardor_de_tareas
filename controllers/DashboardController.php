<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router; 

class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'alertas' => $alertas,
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);
            //validar
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                //generar url
                $proyecto->url = md5(uniqid());

                //almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                //guardar el proyecto
                $resultado = $proyecto->guardar();

                if($resultado) {
                    header('Location: /proyecto?url=' . $proyecto->url);
                }

            }
        }

        $router->render('dashboard/crear_proyecto', [
            'titulo' => 'Crear Proyectos',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $id = $_SESSION['id'];
        $usuario = Usuario::find($id);
        // debuguear($usuario);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {
                //verificar que el email no pertenezca a otro usuario
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error', 'El correo ya está en uso');
                    $alertas = $usuario->getAlertas();
                } else {
                    $usuario->guardar();
                    //asignar nombre nuevo a la barra superior
                    $_SESSION['nombre'] = $usuario->nombre;
                    Usuario::setAlerta('exito', 'Guardado correctamente');
                    $alertas = $usuario->getAlertas();
                }
        
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }



    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_new_pass();
            if(empty($alertas)) {

                $resultado = $usuario->comprobarPassword();

                if($resultado) {
                    //actualizar el password
                    $usuario->password = $usuario->password_nuevo;

                    //eliminar propiedades innecesarias
                    unset($usuario->password_nuevo);
                    unset($usuario->password_actual);
                    unset($usuario->password2);

                    //hashear nuevo password
                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();
                    if($resultado) {
                        Usuario::setAlerta('exito', 'Datos actualizados');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Datos erróneos');
                    $alertas = $usuario->getAlertas();
                }

            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Contraseña',
            'alertas' => $alertas
        ]);
    }



    public static function proyecto(Router $router) {
        session_start();
        isAuth();

        //Permitir SOLO al dueño ver sus proyectos
        $url = $_GET['url'];
        if(!$url) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $url);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('/dashboard/proyecto', [
            'titulo' => $proyecto->proyecto //nombre
        ]);
    }
}