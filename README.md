# ✨ Proyecto Prueba Técnica ✨

Este proyecto consiste en una API construida en **PHP** y **MySQL**, y alojada en un entorno **XAMPP**. Su objetivo principal es gestionar usuarios de manera eficiente. Todo está configurado para ejecutarse en un servidor local.

---

## 🛠️ Requisitos del Entorno

1. **[XAMPP](https://www.apachefriends.org/)**: Incluye Apache, PHP y MySQL.
2. **PHP**: Asegúrate de que tu versión sea compatible con el proyecto.
3. **Git**: Para clonar el repositorio.

---

## 🚀 Pasos para Configurar el Entorno

### 1️⃣ Instalar XAMPP
   - Descarga e instala [XAMPP](https://www.apachefriends.org/).
   - Activa **Apache** y **MySQL** desde el panel de control de XAMPP.

### 2️⃣ Clonar el Repositorio
   - En tu terminal, ejecuta los siguientes comandos para descargar el proyecto:
     ```bash
     git clone https://github.com/TeresaRuiz/prueba_tecnica
     cd prueba_tecnica
     ```

### 3️⃣ Configurar la Base de Datos
   - Abre **phpMyAdmin** desde el panel de control de XAMPP.
   - Crea la base de datos con el siguiente comando en **phpMyAdmin**:
     ```sql
     CREATE DATABASE USUARIOS_API;
     ```
   - **Nota:** Si tienes un archivo SQL para el esquema, impórtalo en la base de datos `USUARIOS_API`.

### 4️⃣ Configurar la Conexión a la Base de Datos
   - Abre el archivo de configuración de conexión, como `config.php`, y asegúrate de que los datos sean correctos:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'USUARIOS_API');
     define('DB_USER', 'tu_usuario');
     define('DB_PASSWORD', 'tu_contraseña');
     ```

### 5️⃣ Iniciar el Servidor
   - Coloca la carpeta del proyecto en `htdocs` dentro de XAMPP (`C:\xampp\htdocs\prueba_tecnica`).
   - Abre tu navegador y accede a `http://localhost/prueba_tecnica`.

---

## 📡 Uso de la API

### Ejemplos de Peticiones en Postman

1. **Vista de Inicio**
   - **URL:** `http://localhost/prueba_tecnica/views/indexx.html`
   - **Método:** `GET`
   - **Código de Estado:** `404 Not Found`

2. **Leer Todos los Usuarios**
   - **URL:** `http://localhost/prueba_tecnica/api/services/usuario.php?action=readAll`
   - **Método:** `GET`
   - **Código de Estado:** `200 OK`

3. **Crear Usuario**
   - **URL:** `http://localhost/prueba_tecnica/api/services/usuario.php?action=createRow`
   - **Método:** `POST`
   - **Código de Estado:** `200 OK`

   **Nota:** La constante `SERVER_URL` está establecida para realizar pruebas de la API.
     ```javascript
     const SERVER_URL = 'http://localhost/prueba_tecnica/api/';
     ```

---

## 🗂️ Estructura del Proyecto

La estructura modular del proyecto facilita su organización y mantenimiento:

- **`api/`**: Contiene servicios, configuración y funciones SQL.
  - **`services/`**: Comunicación entre PHP y JavaScript.
  - **`helpers/`**: Configuración de la base de datos.
  - **`models/`**:
    - **data/**: Validación de datos.
    - **handler/**: Funciones SQL.

- **`controllers/`**: Gestiona la comunicación entre JavaScript, HTML y la API.

- **`resources/`**: Archivos compartidos en el proyecto (CSS, JS, imágenes, etc.).

- **`views/`**: Contiene la interfaz de usuario.

---

