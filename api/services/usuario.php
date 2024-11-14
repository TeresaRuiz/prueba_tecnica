<?php
// Se incluye la clase del modelo.
require_once('../models/data/usuario_data.php');
require_once '../helpers/security.php';

// Configurar las cabeceras de seguridad.
Security::setClickjackingProtection();
Security::setAdditionalSecurityHeaders();

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuarios = new usuarioData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);

    // Se verifica si existe una sesión iniciada como usuarios, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador']) or true) {
        // Se compara la acción a realizar cuando un usuarios ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $usuarios->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $usuarios->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen usuarios registrados';
                }
                break;
            case 'readOne':
                if (!$usuarios->setId($_POST['idUsuario'])) {
                    $result['error'] = $usuarios->getDataError();
                } elseif ($result['dataset'] = $usuarios->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Usuario inexistente';
                }
                break;
            case 'deleteRow':
                if (!$usuarios->setId($_POST['idUsuario'])) {
                    $result['error'] = $usuarios->getDataError();
                } else {
                    if ($usuarios->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Usuario eliminado correctamente.';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el rol.';
                    }
                }
                break;
            case 'createRow':
                // Validar form data
                $_POST = Validator::validateForm($_POST);

                //Verificar contraseñas iguales
                if ($_POST['password_usuario'] !== $_POST['password_confirmar']) {
                    $result['error'] = 'Las contraseñas no coinciden';
                } else {
                    // Assign form values to user object
                    if (
                        !$usuarios->setNombreUsuario($_POST['nombre_usuario'])
                        || !$usuarios->setCorreoUsuario($_POST['correo_usuario'])
                        || !$usuarios->setUsernameUsuario($_POST['username_usuario'])
                        || !$usuarios->setContrasenaUsuario($_POST['password_usuario'])
                        || !$usuarios->setFechaNacimiento($_POST['fecha_nacimiento'])
                        || !$usuarios->setTelefonoUsuario($_POST['telefono_usuario'])
                        || !$usuarios->setDireccionUsuario($_POST['direccion_usuario'])
                        || !$usuarios->setIdRol($_POST['id_rol'])
                        || !$usuarios->setIdEstado($_POST['id_estado'])
                    ) {
                        $result['error'] = $usuarios->getDataError();
                    }
                    // Create new user
                    elseif ($usuarios->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Usuario creado correctamente';
                    } else {
                        $result['error'] = 'Error al crear el usuario';
                    }
                }
                break;
                
            case 'updateRow':
                // Validate form data
                $_POST = Validator::validateForm($_POST);

                // Assign form values to user object
                if (!$usuarios->setId($_POST['idUsuario'])) {
                    $result['error'] = $usuarios->getDataError();
                } elseif (
                    !$usuarios->setNombreUsuario($_POST['nombre_usuario'])
                    || !$usuarios->setCorreoUsuario($_POST['correo_usuario'])
                    || !$usuarios->setUsernameUsuario($_POST['username_usuario'])
                    || !$usuarios->setContrasenaUsuario($_POST['password_usuario']) // Asegúrate de que el campo de contraseña es correcto
                    || !$usuarios->setFechaNacimiento($_POST['fecha_nacimiento'])
                    || !$usuarios->setTelefonoUsuario($_POST['telefono_usuario'])
                    || !$usuarios->setDireccionUsuario($_POST['direccion_usuario'])
                    || !$usuarios->setIdRol($_POST['id_rol'])
                    || !$usuarios->setIdEstado($_POST['id_estado'])
                ) {
                    $result['error'] = $usuarios->getDataError();
                }
                // Update user
                elseif ($usuarios->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario actualizado correctamente';
                } else {
                    $result['error'] = 'Error al actualizar el usuario';
                }
                break;
        }

        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print (json_encode($result));
    } else {
        print (json_encode('Acceso denegado'));
    }
} else {
    print (json_encode('Recurso no disponible'));
}
