// Constantes para completar las rutas de la API
const ESTADO_API = 'services/estado.php';

// Elementos del DOM
const SEARCH_FORM = document.getElementById('searchForm');
const TABLE_BODY = document.getElementById('tableBody');
const SAVE_MODAL = new bootstrap.Modal('#saveModal');
const SAVE_FORM = document.getElementById('saveForm');
const ID_ESTADO = document.getElementById('idEstado');
const NOMBRE_EST = document.getElementById('Estado');
const DESCRIPCION = document.getElementById('descripcion');
const LOADING_INDICATOR = document.getElementById('loadingIndicator');

// Función para mostrar/ocultar el indicador de carga
const toggleLoading = (show) => {
    LOADING_INDICATOR.style.display = show ? 'flex' : 'none';
};

// Cargar la tabla cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
    fillTable();
});

// Evento para el formulario de búsqueda
SEARCH_FORM.addEventListener('submit', (event) => {
    event.preventDefault();
    const FORM = new FormData(SEARCH_FORM);
    fillTable(FORM);
});

// Evento para el formulario de guardar
SAVE_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();
    const action = ID_ESTADO.value ? 'updateRow' : 'createRow';
    const FORM = new FormData(SAVE_FORM);
    
    try {
        toggleLoading(true);
        const DATA = await fetchData(ESTADO_API, action, FORM);
        
        if (DATA.status) {
            SAVE_MODAL.hide();
            await sweetAlert(1, DATA.message, true);
            fillTable();
        } else {
            await sweetAlert(2, DATA.error, false);
        }
    } catch (error) {
        await sweetAlert(2, 'Error al procesar la solicitud', false);
    } finally {
        toggleLoading(false);
    }
});

// Función para llenar la tabla
const fillTable = async (form = null) => {
    try {
        toggleLoading(true);
        TABLE_BODY.innerHTML = '';
        const action = form ? 'searchRows' : 'readAll';
        const DATA = await fetchData(ESTADO_API, action, form);

        if (DATA.status) {
            DATA.dataset.forEach(row => {
                TABLE_BODY.innerHTML += `
                    <tr>
                        <td>${row.id_estado}</td>
                        <td>${row.nombre_estado}</td>
                        <td>${row.descripcion_estado}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" 
                                    onclick="openUpdate(${row.id_estado})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="openDelete(${row.id_estado})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            await sweetAlert(4, DATA.error, true);
        }
    } catch (error) {
        await sweetAlert(2, 'Error al cargar los datos', false);
    } finally {
        toggleLoading(false);
    }
};

// Función para abrir el modal de crear
const openCreate = () => {
    SAVE_MODAL.show();
    SAVE_FORM.reset();
    ID_ESTADO.value = ''; 
};

// Función para abrir el modal de actualizar
const openUpdate = async (id) => {
    try {
        toggleLoading(true);
        const FORM = new FormData();
        FORM.append('idEstado', id);
        const DATA = await fetchData(ESTADO_API, 'readOne', FORM);

        if (DATA.status) {
            SAVE_MODAL.show();
            const ROW = DATA.dataset;
            ID_ESTADO.value = ROW.id_estado;
            NOMBRE_EST.value = ROW.nombre_estado;
            DESCRIPCION.value = ROW.descripcion_estado;
        } else {
            await sweetAlert(2, DATA.error, false);
        }
    } catch (error) {
        await sweetAlert(2, 'Error al cargar el registro', false);
    } finally {
        toggleLoading(false);
    }
};

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el estado de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idEstado', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(ESTADO_API, 'deleteRow', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}
