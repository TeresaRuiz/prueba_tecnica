<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class EstadoHandler
{
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;

    // Método para obtener todos los estados
    public function readAll()
    {
        $sql = 'SELECT id_estado, nombre_estado, descripcion_estado FROM TB_ESTADOS ORDER BY nombre_estado';
        return Database::getRows($sql);
    }

    // Método para crear un nuevo estado
    public function createRow()
    {
        $sql = 'INSERT INTO TB_ESTADOS (nombre_estado, descripcion_estado) VALUES (?, ?)';
        $params = array($this->nombre, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener un estado específico por su ID
    public function readOne()
    {
        $sql = 'SELECT id_estado, nombre_estado, descripcion_estado FROM TB_ESTADOS WHERE id_estado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un estado existente
    public function updateRow()
    {
        $sql = 'UPDATE TB_ESTADOS SET nombre_estado = ?, descripcion_estado = ? WHERE id_estado = ?';
        $params = array($this->nombre, $this->descripcion, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un estado
    public function deleteRow()
    {
        $sql = 'DELETE FROM TB_ESTADOS WHERE id_estado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
