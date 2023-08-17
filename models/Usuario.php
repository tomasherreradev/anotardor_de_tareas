<?php 

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;    
    public $email;    
    public $password;    
    public $password2;    
    public $password_actual;    
    public $password_nuevo;    
    public $token;    
    public $confirmado;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->password2 = $args['password2'] ?? null;
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }



    public function validarNuevaCuenta() : array {
        if(!$this->nombre) {
             self::$alertas['error'][] = 'Tienes que ingresar un nombre';
        }
        
        if(!$this->email) {
            self::$alertas['error'][] = 'Tu email es necesario';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe tener al menos 6 digitos';
        }

        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son diferentes';
        }
        return self::$alertas;
    }




    public function validarLogin() : array {
        if(!$this->email) {
            self::$alertas['error'][] = 'Tu email es necesario';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no válido';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        
        return self::$alertas;
    }


    public function validarPerfil() : array {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'Tienes que ingresar un nombre';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'Tu email es necesario';
        }

        return self::$alertas;
    }



    //hashear password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }




    //generar token
    public function generarToken() : void {
        $this->token = uniqid();
    }





    public function validarEmail() : array {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es necesario';
        } 
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no válido';
        }

        return self::$alertas;
    }



    

    public function validarPassword() : array {
        if(!$this->password) {
            self::$alertas['error'][] = 'Debes ingresar un password';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener por lo menos 6 digitos';
        }


        return self::$alertas;
    }



    public function validar_new_pass () : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'Debes ingresar tu password actual';
        }

        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'Debes ingresar un nuevo password';
        }

        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El password debe contener por lo menos 6 digitos';
        }


        return self::$alertas;
    }



    public function comprobarPassword() : bool {

        return password_verify($this->password_actual, $this->password);
    }
 
}