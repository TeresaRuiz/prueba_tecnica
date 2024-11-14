<?php
// Incluir la clase para el manejo de la base de datos
require_once('../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla de usuarios.
 */
class UsuariosHandler
{
    // Declaración de atributos para el manejo de datos
    protected $id_usuario = null;
    protected $nombre_usuario = null;
    protected $correo_usuario = null;
    protected $username_usuario = null;
    protected $password_usuario = null;
    protected $fecha_registro = null;
    protected $fecha_nacimiento = null;
    protected $telefono_usuario = null;
    protected $direccion_usuario = null;
    protected $id_rol = null;
    protected $id_estado = null;

    /*
     * Función para buscar registros de usuarios.
     * Parámetros: ninguno
     * Retorno: array con los registros encontrados
     */
    public function searchRows()
    {
        // Obtener el valor de búsqueda desde el input del usuario (validado previamente)
        $value = '%' . Validator::getSearchValue() . '%';

        // Consulta SQL
        $sql = 'SELECT id_usuario, nombre_usuario, correo_usuario, username_usuario, telefono_usuario, direccion_usuario, fecha_registro 
                FROM TB_USUARIOS
                WHERE nombre_usuario LIKE ? OR correo_usuario LIKE ? OR telefono_usuario LIKE ?
                ORDER BY nombre_usuario';

        // Parámetros para la consulta
        $params = array($value, $value, $value);

        // Ejecutar la consulta y retornar los resultados
        return Database::getRows($sql, $params);
    }

    /*
     * Función para crear un nuevo registro de usuario.
     * Parámetros: ninguno
     * Retorno: true si la operación fue exitosa, false en caso contrario
     */
    public function createRow()
    {
        // Consulta SQL para insertar un nuevo usuario
        $sql = 'INSERT INTO TB_USUARIOS (nombre_usuario, correo_usuario, username_usuario, password_usuario, fecha_nacimiento, telefono_usuario, direccion_usuario, id_rol, id_estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        // Parámetros para la consulta
        $params = array($this->nombre_usuario, $this->correo_usuario, $this->username_usuario, $this->password_usuario, $this->fecha_nacimiento, $this->telefono_usuario, $this->direccion_usuario, $this->id_rol, $this->id_estado);

        // Ejecutar la consulta de inserción
        return Database::executeRow($sql, $params);
    }

    /*
     * Función para eliminar un registro de usuario.
     * Parámetros: ninguno
     * Retorno: true si la operación fue exitosa, false en caso contrario
     */
    public function deleteRow()
    {
        // Consulta SQL para eliminar un usuario
        $sql = 'DELETE FROM TB_USUARIOS WHERE id_usuario = ?';

        // Parámetros para la consulta
        $params = array($this->id_usuario);

        // Ejecutar la consulta de eliminación
        return Database::executeRow($sql, $params);
    }

    /*
     * Función para actualizar un registro de usuario.
     * Parámetros: ninguno
     * Retorno: true si la operación fue exitosa, false en caso contrario
     */
    public function updateRow()
    {
        // Consulta SQL para actualizar los datos
        $sql = 'UPDATE TB_USUARIOS
                SET nombre_usuario = ?, correo_usuario = ?, username_usuario = ?, fecha_nacimiento = ?, telefono_usuario = ?, 
                    direccion_usuario = ?, id_rol = ?, id_estado = ?
                WHERE id_usuario = ?';

        // Parámetros para la consulta
        $params = array(
            $this->nombre_usuario, $this->correo_usuario, $this->username_usuario,
            $this->fecha_nacimiento, $this->telefono_usuario,
            $this->direccion_usuario, $this->id_rol, $this->id_estado, $this->id_usuario
        );

        // Ejecutar la consulta de actualización
        return Database::executeRow($sql, $params);
    }

    /*
     * Función para obtener todos los registros de usuarios.
     * Parámetros: ninguno
     * Retorno: array con los registros encontrados
     */
    public function readAll()
    {
        // Consulta SQL para obtener todos los usuarios con los nombres de rol y estado
        $sql = 'SELECT 
                    u.id_usuario, 
                    u.nombre_usuario, 
                    u.correo_usuario, 
                    u.username_usuario, 
                    u.telefono_usuario, 
                    u.direccion_usuario, 
                    u.fecha_registro,
                    r.nombre_rol,
                    e.nombre_estado
                FROM TB_USUARIOS u
                LEFT JOIN TB_ROLES r ON u.id_rol = r.id_rol
                LEFT JOIN TB_ESTADOS e ON u.id_estado = e.id_estado
                ORDER BY u.nombre_usuario';

        // Ejecutar la consulta y retornar los resultados
        return Database::getRows($sql);
    }

    /*
     * Función para obtener un registro de usuario por su ID.
     * Parámetros: el ID del usuario
     * Retorno: array con los datos del usuario
     */
    public function readOne()
    {
        // Consulta SQL para obtener un usuario con los nombres de rol y estado
        $sql = 'SELECT 
                    u.id_usuario, 
                    u.nombre_usuario, 
                    u.correo_usuario, 
                    u.username_usuario, 
                    u.telefono_usuario, 
                    u.direccion_usuario, 
                    u.fecha_registro,
                    r.nombre_rol,
                    e.nombre_estado
                FROM TB_USUARIOS u
                LEFT JOIN TB_ROLES r ON u.id_rol = r.id_rol
                LEFT JOIN TB_ESTADOS e ON u.id_estado = e.id_estado
                WHERE u.id_usuario = ?';

        // Parámetros para la consulta (ID del usuario)
        $params = array($this->id_usuario);

        // Ejecutar la consulta y retornar el resultado
        return Database::getRow($sql, $params);
    }
}