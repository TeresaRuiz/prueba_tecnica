// Datos de ejemplo
const mockData = {
    users: [
        { id: '001', name: 'Juan Pérez', email: 'juan@ejemplo.com', role: 'Admin', status: 'Activo', date: '2024-03-13' },
        { id: '002', name: 'María García', email: 'maria@ejemplo.com', role: 'Usuario', status: 'Pendiente', date: '2024-03-13' },
        { id: '003', name: 'Carlos López', email: 'carlos@ejemplo.com', role: 'Usuario', status: 'Activo', date: '2024-03-12' },
        // Agrega más usuarios aquí
    ],
    stats: {
        total: 1234,
        active: 892,
        pending: 145,
        newToday: 24
    }
};

// Funciones de utilidad
function showLoading() {
    document.getElementById('loadingIndicator').style.display = 'block';
}

function hideLoading() {
    document.getElementById('loadingIndicator').style.display = 'none';
}

// Gestión de navegación
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        loadPage(link.dataset.page);
    });
});

// Cargar páginas
function loadPage(page) {
    showLoading();
    setTimeout(() => {
        switch(page) {
            case 'dashboard':
                loadDashboard();
                break;
            case 'users':
                loadUsers();
                break;
            // Agregar más casos según necesidad
        }
        hideLoading();
    }, 500);
}

// Cargar Dashboard
function loadDashboard() {
    const dashboardHTML = `
        <div class="container-fluid px-4 py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <h4>¡Bienvenido al Panel de Control!</h4>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card bg-primary bg-gradient text-white p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Total Usuarios</h6>
                                <h2 class="mb-0">${mockData.stats.total}</h2>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <!-- Repite para otras estadísticas -->
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Usuarios Recientes</h5>
                            <a href="#" onclick="loadPage('users')" class="text-decoration-none">
                                Ver todos <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            ${generateUsersTable(mockData.users.slice(0, 5))}
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    
    document.getElementById('mainContent').innerHTML = dashboardHTML;
}

// Generar tabla de usuarios
function generateUsersTable(users) {
    return `
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                ${users.map(user => `
                    <tr>
                        <td>#${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>
                            <span class="badge bg-${user.status === 'Activo' ? 'success' : 'warning'}">
                                ${user.status}
                            </span>
                        </td>
                        <td>${user.date}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser('${user.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUser('${user.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>`;
}

// Abrir modal de nuevo usuario
function showNewUserModal() {
    const modal = new bootstrap.Modal(document.getElementById('newUserModal'));
    modal.show();
}

// Crear usuario (lógica por añadir)
function createUser() {
    // Aquí puedes agregar la lógica para crear un nuevo usuario
    alert("Usuario creado exitosamente");
    const modal = bootstrap.Modal.getInstance(document.getElementById('newUserModal'));
    modal.hide();
}

// Editar y eliminar usuarios (funciones de ejemplo)
function editUser(id) {
    alert(`Editando usuario con ID: ${id}`);
}

function deleteUser(id) {
    alert(`Eliminando usuario con ID: ${id}`);
}

// Inicializa la página de dashboard por defecto
document.addEventListener('DOMContentLoaded', () => {
    loadPage('dashboard');
});
