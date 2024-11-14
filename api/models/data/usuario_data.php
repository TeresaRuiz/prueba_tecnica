<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../helpers/validator.php');
// Se incluye la clase padre.
require_once('../models/handler/usuario_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla USUARIO.
 */
class usuarioData extends UsuariosHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;

    /*
     *  Métodos para validar y asignar valores de los atributos.
     */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del usuario es incorrecto';
            return false;
        }
    }
    // Método genérico para verificar unicidad de campos
    private function isUnique($campo, $valor)
    {
        if ($this->id_usuario) {  // Si id_usuario está definido, es una actualización
            $checkSql = "SELECT COUNT(*) AS count FROM TB_USUARIOS WHERE $campo = ? AND id_usuario != ?";
            $checkParams = array($valor, $this->id_usuario);
        } else {  // Si id_usuario no está definido, es una creación de usuario
            $checkSql = "SELECT COUNT(*) AS count FROM TB_USUARIOS WHERE $campo = ?";
            $checkParams = array($valor);
        }

        $checkResult = Database::getRow($checkSql, $checkParams);
        return $checkResult['count'] == 0;
    }

    public function setNombreUsuario($value, $min = 2, $max = 50)
    {
        if (!$this->isUnique('nombre_usuario', $value)) {
            $this->data_error = 'El nombre de usuario ya existe';
            return false;
        }
        if (!Validator::validateAlphabetic2($value)) {
            $this->data_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }
    public function setCorreoUsuario($value, $min = 8, $max = 100)
    {
        if (!$this->isUnique('correo_usuario', $value)) {
            $this->data_error = 'El correo ya está registrado';
            return false;
        }
        if (!Validator::validateEmail($value)) {
            $this->data_error = 'El correo no es válido';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->correo_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El correo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setUsernameUsuario($value, $min = 6, $max = 25)
    {
        if (!$this->isUnique('username_usuario', $value)) {
            $this->data_error = 'El nombre de usuario ya está registrado';
            return false;
        }
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'El usuario debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->username_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El usuario debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }


    public function setFechaNacimiento($value)
    {
        // Verificar si el formato de la fecha es correcto (YYYY-MM-DD)
        $fecha = DateTime::createFromFormat('Y-m-d', $value);

        // Si la fecha no es válida o el formato no coincide
        if (!$fecha || $fecha->format('Y-m-d') !== $value) {
            $this->data_error = 'La fecha de nacimiento debe estar en formato YYYY-MM-DD';
            return false;
        }

        // Verificar si la fecha de nacimiento está en el futuro
        $hoy = new DateTime();
        if ($fecha > $hoy) {
            $this->data_error = 'La fecha de nacimiento no puede ser en el futuro';
            return false;
        }

        // Verificar si el usuario tiene al menos 18 años
        $edad_minima = 18;
        $edad = $hoy->diff($fecha)->y;  // Calculamos la diferencia en años
        if ($edad < $edad_minima) {
            $this->data_error = 'Debes tener al menos 18 años para registrarte';
            return false;
        }

        // Si la fecha es válida, asignarla
        $this->fecha_nacimiento = $value;
        return true;
    }

    public function setTelefonoUsuario($value)
    {
        // Validar número de teléfono único
        if (!$this->isUnique('telefono_usuario', $value)) {
            $this->data_error = 'El número de teléfono ya está registrado';
            return false;
        }

        // Eliminar caracteres no numéricos
        $value = preg_replace('/\D/', '', $value);
        
        // Validar longitud del teléfono
        if (strlen($value) >= 8) {
            $this->telefono_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El teléfono debe tener al menos 8 dígitos';
            return false;
        }
    }
    
    public function setDireccionUsuario($value, $min = 6, $max = 5000)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La direccion debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->direccion_usuario = $value;
            return true;
        } else {
            $this->data_error = 'La direccion debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setIdEstado($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_estado = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del estado es incorrecto';
            return false;
        }
    }

    
    public function setIdRol($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_rol = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del rol es incorrecto';
            return false;
        }
    }

    public function setContrasenaUsuario($value)
    {
        // Array con los datos que no se deben incluir en la contraseña
        $user_data = [$this->nombre_usuario, $this->username_usuario, $this->correo_usuario];

        // Pasa los datos del usuario a la función de validación
        if (Validator::validatePassword($value, $user_data)) {
            $this->password_usuario = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            $this->data_error = Validator::getPasswordError();
            return false;
        }
    }
    
    public function getDataError()
    {
        return $this->data_error;
    }
}

