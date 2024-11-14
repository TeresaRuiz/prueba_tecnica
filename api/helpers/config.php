<?php
// Función para cargar variables de entorno desde un archivo .env
function cargarEntorno($archivoEnv) {
    // Verifica si el archivo .env existe
    if (!file_exists($archivoEnv)) {
        throw new Exception("El archivo .env no existe"); // Lanza una excepción si no se encuentra el archivo
    }

    // Lee las líneas del archivo, ignorando líneas vacías y saltos de línea
    $lineas = file($archivoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Recorre cada línea del archivo .env
    foreach ($lineas as $linea) {
        // Omite líneas que son comentarios (inician con #)
        if (strpos(trim($linea), '#') === 0) {
            continue; // Salta a la siguiente iteración si es un comentario
        }

        // Divide la línea en nombre y valor usando '=' como delimitador
        list($nombre, $valor) = explode('=', $linea, 2);
        $nombre = trim($nombre); // Elimina espacios en blanco alrededor del nombre
        $valor = trim($valor);   // Elimina espacios en blanco alrededor del valor

        // Solo agrega la variable a $_ENV si no existe ya
        if (!array_key_exists($nombre, $_ENV)) {
            $_ENV[$nombre] = $valor; // Almacena el valor en el array $_ENV
        }
    }
}

// Llama a la función para cargar el archivo .env desde el directorio actual
cargarEntorno(__DIR__ . '/.env');

// Define constantes usando las variables de entorno cargadas
define('SERVER', $_ENV['SERVER']);     // Define la constante SERVER con el valor de $_ENV['SERVER']
define('DATABASE', $_ENV['DATABASE']); // Define la constante DATABASE con el valor de $_ENV['DATABASE']
define('USERNAME', $_ENV['USERNAME']); // Define la constante USERNAME con el valor de $_ENV['USERNAME']
define('PASSWORD', $_ENV['PASSWORD']); // Define la constante PASSWORD con el valor de $_ENV['PASSWORD']