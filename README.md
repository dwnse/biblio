# Sistema de Biblioteca Digital — BiblioDigital

## Descripción Breve
BiblioDigital es un sistema web de gestión de biblioteca digital desarrollado con PHP 8.2, MySQL y arquitectura MVC. Permite administrar libros, autores, categorías y editoriales con un panel de administración completo y un catálogo público para usuarios.

## Problema que Resuelve
La gestión manual de bibliotecas digitales es ineficiente: no permite búsquedas rápidas, el control de inventario es propenso a errores, y no existe trazabilidad de acciones. BiblioDigital centraliza la administración del acervo bibliográfico con control de acceso por roles, registro de actividades (logs), y una interfaz moderna para la consulta del catálogo.

## Usuarios Objetivo
- **Administradores de biblioteca**: Gestión completa de libros, autores, categorías y editoriales
- **Usuarios / Lectores**: Consulta del catálogo, búsqueda de libros, descarga de materiales
- **Instituciones educativas**: Control de acervo bibliográfico digital

---

## Requisitos Previos

Antes de empezar, asegúrate de tener instalado:

| Software | Versión mínima | Descarga |
|----------|---------------|----------|
| **XAMPP** | 8.x (incluye PHP 8.2 + MySQL + Apache) | [apachefriends.org](https://www.apachefriends.org/) |
| **Composer** | 2.x | [getcomposer.org](https://getcomposer.org/) |
| **Git** | 2.x | [git-scm.com](https://git-scm.com/) |

> **Nota:** XAMPP ya incluye PHP, MySQL y Apache. No necesitas instalarlos por separado.

---

## Instalación Paso a Paso

### 1. Clonar el repositorio

Abre una terminal y navega a la carpeta `htdocs` de XAMPP:

```bash
# Windows (ruta por defecto de XAMPP)
cd C:\xampp\htdocs

# macOS
cd /Applications/XAMPP/htdocs

# Linux
cd /opt/lampp/htdocs
```

Clona el repositorio:

```bash
git clone https://github.com/TU_USUARIO/biblio.git
```

Esto creará la carpeta `biblio/` dentro de `htdocs/`.

### 2. Instalar dependencias (Composer)

Entra a la carpeta del proyecto y ejecuta Composer para generar el autoload PSR-4:

```bash
cd biblio
composer install
```

Si solo necesitas el autoload (sin dependencias externas):

```bash
composer dump-autoload
```

### 3. Crear la base de datos

1. **Inicia XAMPP** → Activa los módulos **Apache** y **MySQL**
2. Abre **phpMyAdmin** en tu navegador: `http://localhost/phpmyadmin`
3. Ve a la pestaña **Importar**
4. Selecciona el archivo `database.sql` ubicado en la raíz del proyecto
5. Haz clic en **Ejecutar**

Esto creará la base de datos `biblioteca_db` con todas sus tablas y un usuario administrador por defecto.

**Alternativa por terminal:**

```bash
mysql -u root -p < database.sql
```

### 4. Configurar la conexión a la base de datos

Abre el archivo `config/DatabaseConnection.php` y verifica que las credenciales coincidan con tu entorno:

```php
private string $host = 'localhost';
private string $dbName = 'biblioteca_db';
private string $username = 'root';       // Usuario de MySQL en XAMPP
private string $password = '';           // Contraseña (vacía por defecto en XAMPP)
```

### 5. Acceder al sistema

Con Apache y MySQL activos en XAMPP, abre tu navegador y visita:

```
http://localhost/biblio/public/
```

### 6. Credenciales por defecto

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Administrador** | `admin@bibliodigital.com` | `admin123` |

Para crear un usuario normal, utiliza la página de registro.

---

## Estructura del Proyecto

```
biblio/
├── config/                    # Configuración (DB, constantes, sesión)
│   ├── constants.php          # Constantes globales y config de sesión
│   └── DatabaseConnection.php # Singleton de conexión PDO
├── public/                    # Archivos accesibles al navegador
│   ├── admin/                 # Panel de administración (9 vistas)
│   ├── api/                   # Endpoints JSON (AJAX)
│   ├── css/                   # Estilos (styles.css)
│   ├── js/                    # JavaScript cliente (app.js)
│   ├── includes/              # Header y Footer compartidos
│   ├── catalogo.php           # Catálogo público
│   ├── libro.php              # Detalle de libro
│   ├── login.php              # Inicio de sesión
│   ├── register.php           # Registro de usuario
│   └── 403.php                # Página de acceso denegado
├── src/                       # Código fuente PHP (PSR-4)
│   ├── Builders/              # Patrón Builder
│   ├── Enums/                 # Enumeraciones (BookStatus, UserRole)
│   ├── Exceptions/            # Excepciones personalizadas
│   ├── Factory/               # Patrón Factory
│   ├── Models/                # Modelos de datos
│   ├── Repositories/          # Capa de acceso a datos
│   ├── Services/              # Lógica de negocio
│   └── Utils/                 # Helpers y Validador
├── composer.json              # Autoload PSR-4
├── database.sql               # Script SQL completo
└── README.md                  # Este archivo
```

---

## Patrones de Diseño Implementados

| Patrón | Ubicación | Propósito |
|--------|-----------|-----------|
| **MVC** | `Models/` · `public/` · `api/` | Separación por capas |
| **Repository** | `Repositories/` | Abstracción de acceso a datos |
| **Singleton** | `DatabaseConnection` | Conexión única a BD |
| **Factory** | `Factory/MaterialFactory.js` | Creación de materiales |
| **Builder** | `Builders/bookBuilder.js` | Construcción paso a paso |

---

## Seguridad

- `password_hash()` / `password_verify()` para contraseñas (bcrypt)
- `session_regenerate_id()` para prevenir Session Fixation
- Cookies: `HttpOnly`, `SameSite=Lax`, `Secure` (si HTTPS)
- Expiración automática de sesión (30 minutos)
- Control de acceso por roles (Admin / Usuario)
- Vista 403 para acceso denegado
- Validación server-side con clase `Validator`
- Prepared Statements PDO (anti SQL Injection)

---

## Solución de Problemas Comunes

| Problema | Solución |
|----------|----------|
| **Página en blanco** | Verifica que Apache y MySQL estén activos en XAMPP |
| **Error de conexión a BD** | Revisa credenciales en `config/DatabaseConnection.php` |
| **Class not found** | Ejecuta `composer dump-autoload` en la raíz del proyecto |
| **Error 404** | Asegúrate de acceder a `/biblio/public/` (no solo `/biblio/`) |
| **Permisos denegados** | Verifica que la carpeta `htdocs/biblio` tenga permisos de lectura |

---

## Tecnologías

| Componente | Tecnología |
|------------|------------|
| Backend | PHP 8.2+ |
| Base de Datos | MySQL 8.0+ (PDO) |
| Frontend | HTML5, CSS3, JavaScript ES6+ |
| Autoload | Composer PSR-4 |
| Servidor | Apache (XAMPP) |
| Control de versiones | Git + GitHub |
