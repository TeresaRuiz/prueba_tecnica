<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class UsuariosHandler
{
    
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre_usuario = null;
    protected $correo_usuario = null;
    protected $username_usuario = null;
    protected $password_usuario = null;
    protected $fecha_registro = null;
    protected $fecha_nacimiento = null;
    protected $telefono_usuario = null;
    protected $direccion_usuario = null;

}
