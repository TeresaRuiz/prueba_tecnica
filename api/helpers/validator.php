<?php
/*
 *	Clase para validar todos los datos de entrada del lado del servidor.
 */
class Validator
{
    /*
     *   Atributos para manejar algunas validaciones.
     */
    private static $filename = null;
    private static $search_value = null;
    private static $password_error = null;
    private static $file_error = null;
    private static $search_error = null;

    // Método para obtener el error al validar una contraseña.
    public static function getPasswordError()
    {
        return self::$password_error;
    }

    // Método para obtener el nombre del archivo validado.
    public static function getFilename()
    {
        return self::$filename;
    }

    // Método para obtener el error al validar un archivo.
    public static function getFileError()
    {
        return self::$file_error;
    }

    // Método para obtener el valor de búsqueda.
    public static function getSearchValue()
    {
        return self::$search_value;
    }

    // Método para obtener el error al validar una búsqueda.
    public static function getSearchError()
    {
        return self::$search_error;
    }

    /*
     *   Método para sanear todos los campos de un formulario (quitar los espacios en blanco al principio y al final).
     *   Parámetros: $fields (arreglo con los campos del formulario).
     *   Retorno: arreglo con los campos saneados del formulario.
     */
    public static function validateForm($fields)
    {
        foreach ($fields as $index => $value) {
            $value = trim($value);
            $fields[$index] = $value;
        }
        return $fields;
    }

    /*
     *   Método para validar un número natural como por ejemplo llave primaria, llave foránea, entre otros.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateNaturalNumber($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a uno.
        if (filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un número natural como por ejemplo llave primaria, llave foránea, entre otros.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateNaturalNumber2($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a uno.
        if (filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => -1000)))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo de imagen.
     *   Parámetros: $file (archivo de un formulario) y $dimension (medida mínima para la imagen).
     *   Retorno: booleano (true si el archivo es correcto o false en caso contrario).
     */
    //Metódo para que me acepte cualquier tamaño de imagen
    public static function validateImageFile($file)
    {
        if (is_uploaded_file($file['tmp_name'])) {
            // Se comprueba si el archivo tiene un tamaño mayor a 2MB.
            if ($file['size'] > 2097152) {
                self::$file_error = 'El tamaño de la imagen debe ser menor a 2MB';
                return false;
            }

            // Se obtienen los datos de la imagen.
            $image = getimagesize($file['tmp_name']);

            // Verificar el tipo de imagen
            if ($image['mime'] == 'image/jpeg' || $image['mime'] == 'image/png') {
                // Se obtiene la extensión del archivo (.jpg o .png) y se convierte a minúsculas.
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                // Se establece un nombre único para el archivo.
                self::$filename = uniqid() . '.' . $extension;
                return true;
            } else {
                self::$file_error = 'El tipo de imagen debe ser jpg o png';
                return false;
            }
        } else {
            self::$file_error = 'No se ha subido ningún archivo';
            return false;
        }
    }

    /*
     *   Método para validar un correo electrónico.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato booleano.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateBoolean($value)
    {
        if ($value == 1 || $value == 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una cadena de texto (letras, digitos, espacios en blanco y signos de puntuación).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateString($value)
    {
        // Sanitizar el valor para prevenir ataques XSS
        $sanitizedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        // Validar que la cadena no contenga caracteres peligrosos.
        // Puedes ajustar esta expresión regular según lo que sea considerado válido para tu campo de dirección.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\.,-\/]+$/', $sanitizedValue)) {
            return $sanitizedValue;
        } else {
            return false; // La cadena no es válida.
        }
    }


    public static function validateColor($value)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        // Permite letras, números, espacios, comas, puntos, punto y coma y colores hexadecimales.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\,\;\.\#]+$/', $value) && preg_match('/^#[0-9A-Fa-f]{3,6}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato alfabético (letras y espacios en blanco).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateAlphabetic($value)
    {
        // Sanitizamos el valor eliminando espacios adicionales y caracteres no deseados.
        $value = trim($value);

        // Evitamos posibles caracteres HTML que podrían causar XSS.
        $sanitizedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        // Se verifica que el valor contenga solo caracteres alfabéticos y espacios.
        if (preg_match('/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]+$/', $sanitizedValue)) {
            return $sanitizedValue;
        } else {
            return false; // El valor no es válido.
        }
    }

    public static function validateAlphabetic2($value)
    {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $value);
    }


    /*
     *   Método para validar un dato alfanumérico (letras, dígitos y espacios en blanco).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateAlphanumeric($value)
    {
        // Sanitizamos el valor eliminando espacios adicionales y caracteres no deseados.
        $value = trim($value);

        // Evitamos posibles caracteres HTML que podrían causar XSS.
        $sanitizedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        // Se verifica que el valor contenga solo letras, dígitos y espacios.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s]+$/', $sanitizedValue)) {
            return $sanitizedValue;
        } else {
            return false; // El valor no es válido.
        }
    }
    /*
     *   Método para validar la longitud de una cadena de texto.
     *   Parámetros: $value (dato a validar), $min (longitud mínima) y $max (longitud máxima).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateLength($value, $min, $max)
    {
        // Se verifica la longitud de la cadena de texto.
        if (strlen($value) >= $min && strlen($value) <= $max) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un dato monetario.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateMoney($value)
    {
        // Se verifica que el número pueda tener cualquier cantidad de dígitos en la parte entera y hasta dos cifras decimales.
        if (preg_match('/^\d+(\.\d{1,2})?$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una contraseña.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePassword($value, $user_data = [])
    {
        // Verifica la longitud mínima y máxima.
        if (strlen($value) < 8) {
            self::$password_error = 'La contraseña es menor a 8 caracteres';
            return false;
        } elseif (strlen($value) > 72) {
            self::$password_error = 'La contraseña es mayor a 72 caracteres';
            return false;
        }

        // Verifica que no tenga espacios en blanco.
        if (preg_match('/\s/', $value)) {
            self::$password_error = 'La contraseña no debe contener espacios en blanco';
            return false;
        }

        // Verifica que contenga al menos una letra, un número y un carácter especial.
        if (!preg_match('/[A-Za-z]/', $value) || !preg_match('/\d/', $value) || !preg_match('/[\W_]/', $value)) {
            self::$password_error = 'La contraseña debe incluir al menos una letra, un número y un carácter especial';
            return false;
        }

        // Verifica que no contenga datos del usuario.
        foreach ($user_data as $data) {
            if (strlen($data) >= 3 && stripos(strtolower($value), strtolower($data)) !== false) {
                self::$password_error = 'La contraseña no debe contener datos del usuario';
                return false;
            }
        }


        // Si pasa todas las validaciones, la contraseña es válida.
        return true;
    }


    /*
     *   Método para validar el formato del DUI (Documento Único de Identidad).
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDUI($value)
    {
        // Se verifica que el número tenga el formato 00000000-0.
        if (preg_match('/^[0-9]{8}[-][0-9]{1}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un número telefónico.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validatePhone($value)
    {
        // Se verifica que el número tenga el formato 0000-0000 y que inicie con 2, 6 o 7.
        if (preg_match('/^[2,6,7]{1}[0-9]{3}[-][0-9]{4}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar una fecha.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateDate($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo con el siguiene orden: año, mes y día.
        $date = explode('-', $value);
        if (checkdate($date[1], $date[2], $date[0])) {
            return true;
        } else {
            return false;
        }
    }
    public static function validateDateTime($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo con el siguiene orden: año, mes y día.
        $date = explode('-', $value);
        if (checkdate($date[1], $date[2], $date[0])) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un valor de búsqueda.
     *   Parámetros: $value (dato a validar).
     *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
     */
    public static function validateSearch($value)
    {
        if (trim($value) == '') {
            self::$search_error = 'Ingrese un valor para buscar';
            return false;
        } elseif (str_word_count($value) > 3) {
            self::$search_error = 'La búsqueda contiene más de 3 palabras';
            return false;
        } elseif (self::validateString($value)) {
            self::$search_value = $value;
            return true;
        } else {
            self::$search_error = 'La búsqueda contiene caracteres prohibidos';
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de subirlo al servidor.
     *   Parámetros: $file (archivo), $path (ruta del archivo) y $name (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
     */
    public static function saveFile($file, $path)
    {
        if (!$file) {
            return false;
        } elseif (move_uploaded_file($file['tmp_name'], $path . self::$filename)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar el cambio de un archivo en el servidor.
     *   Parámetros: $file (archivo), $path (ruta del archivo) y $old_filename (nombre del archivo anterior).
     *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
     */
    public static function changeFile($file, $path, $old_filename)
    {
        if (!self::saveFile($file, $path)) {
            return false;
        } elseif (self::deleteFile($path, $old_filename)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *   Método para validar un archivo al momento de borrarlo del servidor.
     *   Parámetros: $path (ruta del archivo) y $filename (nombre del archivo).
     *   Retorno: booleano (true si el archivo fue borrado del servidor o false en caso contrario).
     */
    public static function deleteFile($path, $filename)
    {
        if ($filename == 'default.png') {
            return true;
        } elseif (@unlink($path . $filename)) {
            return true;
        } else {
            return false;
        }
    }
}
