# Proyecto Prueba Técnica

Este proyecto es una API en PHP con MySQL, alojada en un entorno XAMPP. La API permite gestionar usuarios y está configurada para ser ejecutada en el servidor local.

## Requisitos del Entorno

- [XAMPP](https://www.apachefriends.org/) (Incluye Apache, PHP y MySQL).
- PHP versión 8.2 compatible con el proyecto.
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
   - **Código de Estado Esperado:** `404 Not Found` (Asegúrate de que el nombre del archivo es correcto si se requiere acceso).

2. **Leer Todos los Usuarios**
   - **URL:** `http://localhost/prueba_tecnica/api/services/usuario.php?action=readAll`
   - **Método de Solicitud:** `GET`
   - **Código de Estado Esperado:** `200 OK`

---
