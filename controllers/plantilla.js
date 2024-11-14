// sidebar-template.js
function createSidebar() {
    const sidebarLinks = [
        { href: 'index.html', icon: 'fas fa-home', text: 'Dashboard', page: 'dashboard' },
        { href: 'usuarios.html', icon: 'fas fa-users', text: 'Usuarios', page: 'users' },
        { href: 'roles.html', icon: 'fas fa-user-tag', text: 'Roles', page: 'roles' },
        { href: '#', icon: 'fas fa-cog', text: 'Configuración', page: 'settings' }
    ];

    const sidebarHTML = `
        <div class="sidebar">
            <h4 class="mb-4">
                <i class="fas fa-users-cog text-primary me-2"></i>
                USERICA
            </h4>
            <div class="nav flex-column">
                ${sidebarLinks.map(link => `
                    <a href="${link.href}" 
                       class="nav-link" 
                       data-page="${link.page}">
                        <i class="${link.icon} me-2"></i>${link.text}
                    </a>
                `).join('')}
            </div>
        </div>
    `;

    // Insertar el sidebar
    document.body.insertAdjacentHTML('afterbegin', sidebarHTML);

    // Marcar el link activo basado en la página actual
    const currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'dashboard';
    const activeLink = document.querySelector(`[data-page="${currentPage}"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

// Llamar a la función cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', createSidebar);