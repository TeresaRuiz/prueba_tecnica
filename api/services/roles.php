<?php
// Se incluye la clase del modelo.
require_once('../models/data/rol_data.php');
require_once '../helpers/security.php';

// Configurar las cabeceras de seguridad.
Security::setClickjackingProtection();
Security::setAdditionalSecurityHeaders();

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $roles = new RolesData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);

    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador']) or true) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $roles->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;

            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$roles->setNombre($_POST['nombreRol']) or
                    !$roles->setDesc($_POST['descripcionRol'])
                ) {
                    $result['error'] = $roles->getDataError();
                } elseif ($roles->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Rol creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el rol';
                }
                break;

            case 'readAll':
                if ($result['dataset'] = $roles->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen roles registrados';
                }
                break;

            case 'readOne':
                if (!$roles->setId($_POST['idRol'])) {
                    $result['error'] = $roles->getDataError();
                } elseif ($result['dataset'] = $roles->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Rol inexistente';
                }
                break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);

                // Verificar y establecer los datos del rol
                if (
                    !$roles->setId($_POST['idRol']) ||
                    !$roles->setNombre($_POST['nombreRol']) ||
                    !$roles->setDesc($_POST['descripcionRol'])
                ) {
                    $result['error'] = $roles->getDataError();
                } elseif ($roles->updateRow()) {
                    $nombreRol = $_POST['nombreRol']; // Nombre actualizado
                    $result['status'] = 1;
                    $result['message'] = "Rol '$nombreRol' modificado correctamente.";
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el rol.';
                }
                break;

            case 'deleteRow':
                if (!$roles->setId($_POST['idRol'])) {
                    $result['error'] = $roles->getDataError();
                } else {
                    if ($roles->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Rol eliminado correctamente.';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el rol.';
                    }
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
