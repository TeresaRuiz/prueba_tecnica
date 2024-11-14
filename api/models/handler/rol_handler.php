<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class RolHandler
{
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;

    // Método para obtener todos los roles
    public function readAll()
    {
        $sql = 'SELECT id_rol, nombre_rol, descripcion_rol FROM TB_ROLES ORDER BY nombre_rol';
        return Database::getRows($sql);
    }

    // Método para crear un nuevo rol
    public function createRow()
    {
        $sql = 'INSERT INTO TB_ROLES (nombre_rol, descripcion_rol) VALUES (?, ?)';
        $params = array($this->nombre, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener un rol específico por su ID
    public function readOne()
    {
        $sql = 'SELECT id_rol, nombre_rol, descripcion_rol FROM TB_ROLES WHERE id_rol = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un rol existente
    public function updateRow()
    {
        $sql = 'UPDATE TB_ROLES SET nombre_rol = ?, descripcion_rol = ? WHERE id_rol = ?';
        $params = array($this->nombre, $this->descripcion, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un rol
    public function deleteRow()
    {
        $sql = 'DELETE FROM TB_ROLES WHERE id_rol = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

}
