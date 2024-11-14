<?php
// Se incluye la clase del modelo.
require_once('../models/data/estado_data.php');
require_once '../helpers/security.php';

// Configurar las cabeceras de seguridad.
Security::setClickjackingProtection();
Security::setAdditionalSecurityHeaders();

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $estados = new estadoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);

    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador']) or true) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $estados->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;

            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$estados->setNombre($_POST['Estado']) or
                    !$estados->setDesc($_POST['descripcion'])
                ) {
                    $result['error'] = $estados->getDataError();
                } elseif ($estados->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Estado';
                }
                break;

            case 'readAll':
                if ($result['dataset'] = $estados->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen estados registrados';
                }
                break;

            case 'readOne':
                if (!$estados->setId($_POST['idEstado'])) {
                    $result['error'] = $estados->getDataError();
                } elseif ($result['dataset'] = $estados->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Estado inexistente';
                }
                break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);

                // Verificar y establecer los datos del Estado
                if (
                    !$estados->setId($_POST['idEstado']) ||
                    !$estados->setNombre($_POST['Estado']) ||
                    !$estados->setDesc($_POST['descripcion'])
                ) {
                    $result['error'] = $estados->getDataError();
                } elseif ($estados->updateRow()) {
                    $nombreEstado = $_POST['Estado']; // Nombre actualizado
                    $result['status'] = 1;
                    $result['message'] = "Estado '$nombreEstado' modificado correctamente.";
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el Estado.';
                }
                break;

            case 'deleteRow':
                if (!$estados->setId($_POST['idEstado'])) {
                    $result['error'] = $estados->getDataError();
                } else {
                    if ($estados->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Estado eliminado correctamente.';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el Estado.';
                    }
                }
                break;
        }

        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al contEstadoador.
        print (json_encode($result));
    } else {
        print (json_encode('Acceso denegado'));
    }
} else {
    print (json_encode('Recurso no disponible'));
}
