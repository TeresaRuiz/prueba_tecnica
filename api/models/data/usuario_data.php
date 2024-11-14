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
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del usuario es incorrecto';
            return false;
        }
    }
    public function setNombre($value, $min = 2, $max = 50)
    {
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
    public function setCorreo($value, $min = 8, $max = 100)
    {
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
    public function setUsername($value, $min = 6, $max = 25)
    {
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

    public function setFecha($value)
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

    public function setTelefono($value)
    {
        // Eliminar todos los caracteres no numéricos del número de teléfono
        $value = preg_replace('/\D/', '', $value);
        
        // Validar que el número de teléfono tenga al menos 7 dígitos
        if (strlen($value) >= 8) {
            $this->telefono_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El teléfono debe tener al menos 8 dígitos';
            return false;
        }
    }
    
    public function setDireccion($value, $min = 6, $max = 5000)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->data_error = 'La direccion debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->direccion = $value;
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

    
    public function setNombreRol($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_rol = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del rol es incorrecto';
            return false;
        }
    }

    public function setClave($value)
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

