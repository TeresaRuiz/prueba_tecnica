# Proyecto Prueba Técnica

Este proyecto es una API en PHP con MySQL, alojada en un entorno XAMPP. La API permite gestionar usuarios y está configurada para ser ejecutada en el servidor local.

## Requisitos del Entorno

- [XAMPP](https://www.apachefriends.org/) (Incluye Apache, PHP y MySQL).
- PHP versión compatible con el proyecto.
- Git para clonar el repositorio.

## Instrucciones para Configurar el Entorno

1. **Descargar XAMPP**
   - Instala [XAMPP](https://www.apachefriends.org/) y asegúrate de activar Apache y MySQL desde el panel de control.

2. **Clonar el Repositorio**
   - En una terminal, ejecuta los siguientes comandos:
     ```bash
     git clone https://github.com/TeresaRuiz/prueba_tecnica
     cd prueba_tecnica
     ```

3. **Configurar la Base de Datos**
   - Abre `phpMyAdmin` desde el panel de control de XAMPP.
   - Crea la base de datos ejecutando el siguiente comando SQL en `phpMyAdmin`:
     ```sql
     CREATE DATABASE USUARIOS_API;
     ```
   - **Nota:** Puede usar un archivo SQL para el esquema de la base de datos, impórtandolo en `phpMyAdmin` en la base de datos `USUARIOS_API`.

4. **Configurar el Archivo de Conexión a la Base de Datos**
   - En el archivo de configuración de conexión (por ejemplo, `config.php`), asegúrate de que los datos de conexión sean correctos:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'USUARIOS_API');
     define('DB_USER', 'tu_usuario');
     define('DB_PASSWORD', 'tu_contraseña');
     ```

5. **Iniciar el Servidor**
   - Coloca el proyecto en la carpeta `htdocs` de XAMPP (`C:\xampp\htdocs\prueba_tecnica`).
   - Abre tu navegador y accede a `http://localhost/prueba_tecnica`.

## Uso de la API

### Ejemplos de Peticiones en Postman

1. **Vista de Inicio**
   - **URL:** `http://localhost/prueba_tecnica/views/indexx.html`
   - **Método de Solicitud:** `GET`
   - **Código de Estado Esperado:** `404 Not Found`

2. **Leer Todos los Usuarios**
   - **URL:** `http://localhost/prueba_tecnica/api/services/usuario.php?action=readAll`
   - **Método de Solicitud:** `GET`
   - **Código de Estado Esperado:** `200 OK`

3. **Crear Usuario**
   - **URL:** `http://localhost/prueba_tecnica/api/services/usuario.php?action=createRow`
   - **Método de Solicitud:** `POST`
   - **Código de Estado Esperado:** `200 OK`

### URL para Pruebas
   - La constante `SERVER_URL` se establece para realizar pruebas de la API:
     ```javascript
     const SERVER_URL = 'http://localhost/prueba_tecnica/api/';
     ```

## Estructura del Proyecto

El proyecto sigue una estructura modular para facilitar el mantenimiento y la organización:

- `api/`
  - **services/**: Comunicación entre PHP y JavaScript.
  - **helpers/**: Configuración de la base de datos.
  - **models/**:
    - **data/**: Validación de datos.
    - **handler/**: Funciones SQL.

- `controllers/`: Maneja la comunicación entre JavaScript, HTML y la API.

- `resources/`: Archivos utilizados en todo el proyecto (CSS, JS compartido, etc.).

- `views/`: Vista de la página, con interfaces de usuario.

