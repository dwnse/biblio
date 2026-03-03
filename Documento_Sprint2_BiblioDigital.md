# SEGUNDA PRESENTACIÓN DEL PROYECTO — BiblioDigital
## Documento Técnico bajo Metodología XP

---

**Materia:** Tecnología Web II  
**Fecha de Entrega:** 02/03/2026  
**Modalidad:** Grupal  
**Nombre del Proyecto:** BiblioDigital — Sistema de Biblioteca Digital  
**Representante:** Jhosmar  
**Grupo / Equipo:** Equipo BiblioDigital  
**Iteración actual:** Sprint 2  

---

## 1.1 Presentación

### Introducción del Proyecto

BiblioDigital es un sistema web de gestión de biblioteca digital que permite administrar libros, autores, categorías y editoriales. Desarrollado con PHP 8.2, MySQL y arquitectura MVC, ofrece un catálogo público para visitantes y un panel de administración completo con control de acceso por roles.

### Funcionalidades desarrolladas en la Iteración 2
- CRUD completo de **Autores**, **Categorías**, **Editoriales** y **Libros**
- Implementación de patrones **Factory** y **Builder**
- **Seguridad de sesiones** mejorada (HttpOnly, SameSite, Secure, session_regenerate_id, expiración controlada)
- **Vista 403** para control de acceso denegado
- **Manejo de excepciones** personalizadas con logging de auditoría
- **Refactorización** de API endpoints para prevenir errores JSON
- **Output buffering** en endpoints API para robustez

### Qué quedó pendiente
- Módulo de reseñas/calificaciones
- Tracking detallado de descargas
- Búsqueda avanzada con filtros combinados
- Reportes exportables

### Iteración Corta

**Duración:** 1 semana

#### Historias de Usuario del Sprint 2:

| ID | Descripción | Prioridad | Criterios de Aceptación | Estado | Responsable |
|---|---|---|---|---|---|
| HU-03 | Como administrador, quiero que solo los usuarios autenticados puedan descargar PDF, para proteger los activos | Alta | Botón cambia a "Inicia sesión" si no hay sesión; redirige a login | Completada | Equipo |
| HU-05 | Como administrador, quiero CRUD de Autores para mantener la base de datos actualizada | Alta | Vista tabla, formulario validado, protección eliminación referencial | Completada | Equipo |
| HU-06 | Como administrador, quiero CRUD de Categorías para clasificar libros | Media | Lista en panel, formulario asíncrono, estados activa/inactiva | Completada | Equipo |
| HU-07 | Como administrador, quiero CRUD de Editoriales con datos de contacto | Alta | Formulario con País, Teléfono, Email, Sitio Web validados | Completada | Equipo |
| HU-08 | Como administrador, quiero registrar libros con ISBN, autor, categoría | Alta | Validación RegEx ISBN (10/13 dígitos), año ≤ año actual | Completada | Equipo |

**Justificación de selección:** Se priorizaron los CRUD completos de entidades principales que conforman el corazón del sistema de biblioteca. Sin estas funcionalidades, el catálogo no tendría contenido gestionable.

**Objetivos de la iteración:**
1. Completar todos los módulos CRUD del panel administrativo
2. Implementar patrones de diseño Factory y Builder
3. Reforzar la seguridad de sesiones y control de acceso
4. Mejorar el manejo de errores y excepciones

**Entregable generado:** Sistema funcional completo con CRUD de todas las entidades, seguridad reforzada, patrones implementados y documentación técnica.

### Refactorización

**Qué se mejoró:** Los endpoints API (`catalog.php`, `books.php`, `auth.php`)

**Por qué:** Los endpoints devolvían errores PHP en formato HTML cuando ocurrían warnings/notices, lo que rompía el parseo JSON en el frontend (`SyntaxError: Unexpected token '<'`).

**Qué problema se resolvió:**
- Se agregó `ini_set('display_errors', '0')` y output buffering (`ob_start()`) en cada API
- Se reemplazó `Helpers::requireLogin()` (que hacía redirect HTML) por verificaciones JSON
- Se cambió `catch (Exception)` por `catch (Throwable)` para capturar todos los errores
- Se mejoró `app.js` con parseo JSON seguro (`response.text()` + `JSON.parse()` con try/catch)
- Se agregó método faltante `LoggerService::logAction()`

### Integración Continua
- **Frecuencia de commits:** Múltiples commits diarios con mensajes descriptivos
- **Integración:** Se integraba el código después de verificar cada módulo individual
- **Pruebas antes de push:** Se realizaban pruebas manuales en navegador antes de cada push

---

## 1.2 Diseño Simple del Sistema

### 1.2.1 Arquitectura del Proyecto

#### Estructura de Carpetas
```
biblio/
├── config/                     # Capa de Configuración
│   ├── constants.php           # Constantes globales y config de sesión
│   └── DatabaseConnection.php  # Singleton de conexión PDO
├── public/                     # Capa de Presentación (Vista)
│   ├── admin/                  # Vistas del panel administrativo
│   │   ├── autores.php         # Lista de autores
│   │   ├── autor_form.php      # Formulario autor
│   │   ├── categorias.php      # Lista de categorías
│   │   ├── categoria_form.php  # Formulario categoría
│   │   ├── editoriales.php     # Lista de editoriales
│   │   ├── editorial_form.php  # Formulario editorial
│   │   ├── libros.php          # Lista de libros
│   │   ├── libro_form.php      # Formulario libro
│   │   └── index.php           # Dashboard administrativo
│   ├── api/                    # Controladores API (JSON)
│   │   ├── auth.php            # Login / Registro
│   │   ├── books.php           # CRUD Libros
│   │   ├── catalog.php         # CRUD Autores/Categorías/Editoriales
│   │   └── logout.php          # Cierre de sesión
│   ├── includes/               # Componentes compartidos
│   │   ├── header.php          # Cabecera HTML + Navbar
│   │   └── footer.php          # Pie de página + Scripts
│   ├── css/styles.css          # Estilos globales
│   ├── js/app.js               # JavaScript cliente
│   ├── 403.php                 # Vista acceso denegado
│   ├── catalogo.php            # Catálogo público
│   ├── libro.php               # Detalle de libro
│   ├── login.php               # Inicio de sesión
│   ├── register.php            # Registro
│   └── index.php               # Entrada (redirige a catálogo)
├── src/                        # Capa de Lógica de Negocio
│   ├── Builders/               # Patrón Builder
│   │   └── bookBuilder.js
│   ├── Enums/                  # Enumeraciones
│   │   ├── BookStatus.php
│   │   ├── UserRole.php
│   │   └── UserStatus.php
│   ├── Exceptions/             # Excepciones personalizadas
│   │   ├── BaseException.php
│   │   ├── AuthenticationException.php
│   │   ├── BookNotFoundException.php
│   │   └── UserNotFoundException.php
│   ├── Factory/                # Patrón Factory
│   │   └── MaterialFactory.js
│   ├── Models/                 # Modelos de datos
│   │   ├── Author.php
│   │   ├── Book.php
│   │   ├── Category.php
│   │   ├── Editorial.php
│   │   └── User.php
│   ├── Repositories/           # Capa de acceso a datos
│   │   ├── IRepository.php     # Interfaz
│   │   ├── BaseRepository.php  # Abstracta base
│   │   ├── AuthorRepository.php
│   │   ├── BookRepository.php
│   │   ├── CategoryRepository.php
│   │   ├── EditorialRepository.php
│   │   └── UserRepository.php
│   ├── Services/               # Servicios de negocio
│   │   ├── BookService.php
│   │   ├── CatalogService.php
│   │   ├── LoggerService.php
│   │   └── UserService.php
│   └── Utils/                  # Utilidades
│       ├── Helpers.php
│       └── Validator.php
├── composer.json               # Autoload PSR-4
├── database.sql                # Script SQL completo
└── README.md                   # Documentación
```

#### Separación por capas
1. **Presentación (Vista):** `public/` — Archivos PHP con HTML que renderizan la interfaz
2. **Controlador:** `public/api/` — Endpoints que reciben requests, validan y delegan a Services
3. **Lógica de Negocio:** `src/Services/` — Orquesta operaciones entre repositorios
4. **Acceso a Datos:** `src/Repositories/` — Queries SQL con PDO y Prepared Statements
5. **Configuración:** `config/` — Conexión DB (Singleton) y constantes globales

#### Uso de Namespaces
```php
namespace App\Services;       // LoggerService, BookService, etc.
namespace App\Repositories;   // BaseRepository, BookRepository, etc.
namespace App\Models;         // Book, Author, User, etc.
namespace App\Exceptions;     // BaseException, AuthenticationException, etc.
namespace App\Enums;          // BookStatus, UserRole, UserStatus
namespace App\Utils;          // Helpers, Validator
namespace App\Config;         // DatabaseConnection
```

#### Autoload con Composer
```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/",
            "App\\Config\\": "config/"
        }
    }
}
```

#### Diagrama de Arquitectura
```
┌─────────────────────────────────────────────────┐
│                   NAVEGADOR                      │
│         (HTML/CSS/JS — app.js, Toast)            │
└────────────────────┬────────────────────────────┘
                     │ HTTP (fetch/AJAX)
                     ▼
┌─────────────────────────────────────────────────┐
│              CONTROLADORES (api/)                │
│   auth.php │ books.php │ catalog.php │ logout    │
│   (Validación, JSON response, ob_start)          │
└────────────────────┬────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────┐
│              SERVICIOS (Services/)               │
│  UserService │ BookService │ CatalogService      │
│  LoggerService (Auditoría de acciones)           │
└────────────────────┬────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────┐
│            REPOSITORIOS (Repositories/)           │
│  IRepository (interfaz) → BaseRepository (CRUD)  │
│  BookRepo │ AuthorRepo │ CategoryRepo │ UserRepo │
└────────────────────┬────────────────────────────┘
                     │ PDO Prepared Statements
                     ▼
┌─────────────────────────────────────────────────┐
│              MySQL (biblioteca_db)               │
│  10 tablas normalizadas: roles, usuarios,        │
│  libros, autores, categorias, editorial,         │
│  libro_autor, libro_categoria, descargas,        │
│  resenas, logs, recomendaciones                  │
└─────────────────────────────────────────────────┘
```

---

## 1.3 Implementación Técnica

### 1.3.1 Programación Orientada a Objetos

#### Clases implementadas en esta iteración:

| Clase | Tipo | Propósito |
|---|---|---|
| `DatabaseConnection` | Concreta (Singleton) | Gestión de conexión PDO única a MySQL |
| `IRepository` | Interfaz | Contrato CRUD para todos los repositorios |
| `BaseRepository` | Abstracta | Implementación base de operaciones CRUD genéricas |
| `AuthorRepository` | Concreta | Acceso a datos de autores (findActive, toggleStatus) |
| `BookRepository` | Concreta | Queries complejas de libros con relaciones M:N |
| `CategoryRepository` | Concreta | Acceso a datos de categorías |
| `EditorialRepository` | Concreta | Acceso a datos de editoriales |
| `UserRepository` | Concreta | Acceso a datos de usuarios (findByEmail, emailExists) |
| `Book` | Modelo | Encapsula datos de libro con getters/setters y toArray() |
| `Author` | Modelo | Encapsula datos de autor |
| `Category` | Modelo | Encapsula datos de categoría |
| `Editorial` | Modelo | Encapsula datos de editorial |
| `User` | Modelo | Encapsula datos de usuario |
| `BookService` | Servicio | Lógica de negocio de libros (crear con relaciones M:N) |
| `CatalogService` | Servicio | Gestión de autores, categorías y editoriales |
| `UserService` | Servicio | Registro, login con password_hash, sesiones |
| `LoggerService` | Servicio | Registro de auditoría en tabla logs |
| `BaseException` | Abstracta | Excepción base con mensaje amigable para usuario |
| `AuthenticationException` | Concreta | Error de autenticación |
| `BookNotFoundException` | Concreta | Libro no encontrado |
| `UserNotFoundException` | Concreta | Usuario no encontrado |
| `BookStatus` | Enum | Estados de libro (disponible, no_disponible) |
| `UserRole` | Enum | Roles de usuario (Admin=1, Usuario=2) |
| `UserStatus` | Enum | Estados de usuario (activo, inactivo) |
| `Helpers` | Utilidad (static) | Redirección, sesión, sanitización, flash messages |
| `Validator` | Utilidad | Validación server-side (required, email, url, regex, etc.) |

### 1.3.2 Modelado

#### Diagrama de Casos de Uso

```
                     ┌──────────────────────────────────┐
                     │        BiblioDigital              │
                     │                                   │
  ┌────────┐         │  ┌─────────────────────────┐     │
  │Visitante├─────────┤──┤ Ver Catálogo Público     │     │
  └────┬───┘         │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       ├─────────────┤──┤ Ver Detalles de Libro    │     │
       │             │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       └─────────────┤──┤ Registrarse              │     │
                     │  └─────────────────────────┘     │
  ┌────────┐         │  ┌─────────────────────────┐     │
  │ Usuario├─────────┤──┤ Iniciar/Cerrar Sesión    │     │
  └────┬───┘         │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       └─────────────┤──┤ Descargar Libro (PDF)    │     │
                     │  └─────────────────────────┘     │
  ┌────────┐         │  ┌─────────────────────────┐     │
  │  Admin ├─────────┤──┤ Gestionar Libros (CRUD)  │     │
  └────┬───┘         │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       ├─────────────┤──┤ Gestionar Autores (CRUD) │     │
       │             │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       ├─────────────┤──┤ Gestionar Categorías     │     │
       │             │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       ├─────────────┤──┤ Gestionar Editoriales    │     │
       │             │  └─────────────────────────┘     │
       │             │  ┌─────────────────────────┐     │
       └─────────────┤──┤ Ver Dashboard + Logs     │     │
                     │  └─────────────────────────┘     │
                     └──────────────────────────────────┘
```

#### Diagrama de Clases (simplificado)

```
┌──────────────────────┐
│    <<interface>>      │
│    IRepository        │
├──────────────────────┤
│ + findAll(): array    │
│ + findById(id): ?arr  │
│ + create(data): int   │
│ + update(id,data):bool│
│ + delete(id): bool    │
└──────────┬───────────┘
           │ implements
           ▼
┌──────────────────────┐
│  <<abstract>>         │
│  BaseRepository       │
├──────────────────────┤
│ # db: PDO             │
│ # table: string       │
│ # primaryKey: string  │
├──────────────────────┤
│ + findAll()           │
│ + findById()          │
│ + create()            │
│ + update()            │
│ + delete()            │
│ + count()             │
│ + paginate()          │
└──────────┬───────────┘
           │ extends
     ┌─────┼─────┬──────────┬──────────┐
     ▼     ▼     ▼          ▼          ▼
  Author  Book  Category  Editorial  User
  Repo    Repo  Repo      Repo       Repo

┌──────────────────────┐     ┌──────────────────┐
│  <<abstract>>         │     │ DatabaseConnection│
│  BaseException        │     │  <<singleton>>    │
├──────────────────────┤     ├──────────────────┤
│ # userMessage: string │     │ - instance: self  │
├──────────────────────┤     │ - connection: PDO │
│ + getUserMessage()    │     ├──────────────────┤
└──────────┬───────────┘     │ + getInstance()   │
     ┌─────┼─────┐           │ + getConnection() │
     ▼     ▼     ▼           └──────────────────┘
 AuthExc BookExc UserExc

┌──────────────────────┐
│  BookService          │
├──────────────────────┤
│ - bookRepository      │
│ - logger              │
├──────────────────────┤
│ + createBook()        │
│ + updateBook()        │
│ + deleteBook()        │
│ + getBookById()       │
│ + searchBooks()       │
│ + getStats()          │
└──────────────────────┘
```

---

## 1.4 Modelo Relacional

### Tablas normalizadas (3FN)

La base de datos `biblioteca_db` contiene **10 tablas** normalizadas:

| Tabla | PK | FK | Descripción |
|---|---|---|---|
| `roles` | id_rol | — | Roles del sistema (Admin, Usuario) |
| `usuarios` | id_usuario | id_rol → roles | Usuarios registrados |
| `categorias` | id_categoria | — | Géneros/categorías literarias |
| `autores` | id_autor | — | Autores literarios |
| `editorial` | id_editorial | — | Casas editoriales |
| `libros` | id_libro | id_editorial → editorial | Libros del catálogo |
| `libro_autor` | id_libro_autor | id_libro, id_autor | Relación M:N libro-autor |
| `libro_categoria` | id_libro_categoria | id_libro, id_categoria | Relación M:N libro-categoría |
| `descargas` | id_descarga | id_usuario, id_libro | Registro de descargas |
| `resenas` | id_resena | id_usuario, id_libro | Reseñas y calificaciones |
| `logs` | id_log | id_usuario | Auditoría de acciones |
| `recomendaciones` | id_recomendacion | id_libro | Recomendaciones de libros |

### Relaciones
- **1:N** — roles → usuarios, editorial → libros, usuarios → descargas, usuarios → logs
- **M:N** — libros ↔ autores (via libro_autor), libros ↔ categorias (via libro_categoria)
- **CHECK** — resenas.calificacion BETWEEN 1 AND 5
- **ENUM** — estados controlados (activo/inactivo, disponible/no_disponible, visible/oculta)

---

## 1.5 Sistema de Login Funcional

### Implementación:

**password_hash()** — `UserService::register()`:
```php
$data['contrasena'] = password_hash($data['contrasena'], PASSWORD_BCRYPT);
```

**password_verify()** — `UserService::login()`:
```php
if (!password_verify($password, $user['contrasena'])) {
    throw new AuthenticationException('La contraseña es incorrecta.');
}
```

**Control de sesión** — Variables de sesión establecidas en login:
```php
$_SESSION['user_id'] = $user['id_usuario'];
$_SESSION['user_name'] = $user['nombre'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = (int) $user['id_rol'];
$_SESSION['user_role_name'] = $user['rol_nombre'];
$_SESSION['last_activity'] = time();
```

**Logout seguro** — `UserService::logout()`:
```php
session_unset();
session_destroy();
```

---

## 1.6 Seguridad de Sesiones

Implementado en `config/constants.php`:

```php
session_set_cookie_params([
    'lifetime' => 1800,           // 30 minutos
    'path'     => '/',
    'secure'   => $isSecure,      // Solo HTTPS si disponible
    'httponly'  => true,           // No accesible via JavaScript
    'samesite'  => 'Lax',         // Protección CSRF básica
]);
```

**session_regenerate_id()** — En `UserService::login()`:
```php
session_regenerate_id(true); // Previene Session Fixation
```

**Expiración controlada** — En `constants.php`:
```php
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();
```

---

## 1.7 Control de Acceso

### Protección por rol
```php
// Helpers.php
public static function requireAdmin(): void {
    self::requireLogin();
    if (!self::isAdmin()) {
        self::setFlash('error', 'No tienes permisos...');
        header("Location: " . BASE_URL . "/403.php");
        exit;
    }
}
```

### Vista 403
Archivo `public/403.php` con HTTP status code 403, mensaje amigable y botones de navegación.

### Redirección si no autenticado
```php
public static function requireLogin(): void {
    if (!self::isLoggedIn()) {
        self::setFlash('error', 'Debes iniciar sesión...');
        self::redirect('/login.php');
    }
}
```

### En API endpoints (JSON)
```php
if (!Helpers::isLoggedIn()) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión...']);
    exit;
}
```

---

## 1.8 Patrones de Diseño

### 1.8.1 Patrón MVC
- **Model:** `src/Models/` (Book, Author, Category, Editorial, User)
- **View:** `public/` (PHP + HTML templates con header/footer)
- **Controller:** `public/api/` (auth.php, books.php, catalog.php)

### 1.8.2 Patrón Singleton
Implementado en `DatabaseConnection`:
```php
private static ?DatabaseConnection $instance = null;

public static function getInstance(): self {
    if (self::$instance === null) {
        self::$instance = new self();
    }
    return self::$instance;
}

private function __construct() { /* ... */ }
private function __clone() { }
```

### 1.8.3 Patrón Repository
- `IRepository` (interfaz): Define contrato CRUD
- `BaseRepository` (abstracta): Implementación genérica con PDO
- Repositorios concretos heredan y especializan

### 1.8.4 Patrón Factory
`src/Factory/MaterialFactory.js` — Crea diferentes tipos de materiales bibliográficos.

### 1.8.5 Patrón Builder
`src/Builders/bookBuilder.js` — Construye objetos Book paso a paso con API fluida.

---

## 1.9 Manejo de Excepciones y Errores

### Try-Catch-Finally
Todos los endpoints API usan try-catch con `finally` en el JS:
```php
// PHP (catalog.php)
try {
    // Lógica de negocio
} catch (\Throwable $e) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
```

```javascript
// JS (app.js)
try {
    const text = await response.text();
    data = JSON.parse(text);
} catch (parseError) {
    Toast.show('Error del servidor...', 'error');
} finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalHtml;
}
```

### Excepciones Personalizadas
```
BaseException (abstracta)
├── AuthenticationException — Login/registro fallido
├── BookNotFoundException   — Libro no encontrado
└── UserNotFoundException   — Usuario no encontrado
```

Cada una tiene `getUserMessage()` para mensajes amigables al usuario.

### Logging
`LoggerService` registra todas las acciones en la tabla `logs`:
- ID de usuario, acción realizada, tabla afectada, registro afectado, IP, timestamp
- Métodos: `log()` (genérico) y `logAction()` (descriptivo para CatalogService)

---

## 1.10 Pruebas y Validación

### Pruebas manuales realizadas

| # | Prueba | Resultado |
|---|---|---|
| 1 | Registro con email válido | ✅ Usuario creado, redirect a login |
| 2 | Registro con email duplicado | ✅ Error "El email ya está registrado" |
| 3 | Login con credenciales válidas | ✅ Sesión creada, redirect según rol |
| 4 | Login con contraseña incorrecta | ✅ Error amigable mostrado |
| 5 | Crear autor con nombre válido | ✅ Autor creado, redirect a lista |
| 6 | Crear autor sin nombre | ✅ Validación HTML5 bloquea envío |
| 7 | Editar categoría existente | ✅ Datos actualizados correctamente |
| 8 | Eliminar autor con libros asignados | ✅ Error protección referencial |
| 9 | Eliminar editorial sin libros | ✅ Editorial eliminada exitosamente |
| 10 | Crear libro con ISBN inválido | ✅ Validación regex rechaza formato |
| 11 | Acceso admin sin sesión | ✅ Redirect a login.php |
| 12 | Acceso admin como usuario normal | ✅ Redirect a 403.php |
| 13 | Sesión expira tras 30 min | ✅ Session destroyed automáticamente |
| 14 | API devuelve JSON incluso con errores PHP | ✅ Output buffering funciona |

### Validación de criterios de aceptación
Todos los criterios de las HU-03 a HU-09 fueron verificados y cumplidos según las pruebas manuales.

---

## 1.11 Registro del Daily Standup

### Día 1:
**Miembro 1 (Jhosmar):**
- ¿Qué hice ayer? Diseñé la estructura de carpetas y configuré Composer PSR-4
- ¿Qué haré hoy? Implementaré el CRUD de autores y categorías
- ¿Qué dificultades tuve? Configurar el autoload correctamente con namespaces

### Día 2:
**Miembro 1 (Jhosmar):**
- ¿Qué hice ayer? Completé CRUD de autores y categorías con validación
- ¿Qué haré hoy? Implementaré CRUD de editoriales y el módulo de libros
- ¿Qué dificultades tuve? Las relaciones M:N del formulario de libros requirieron selects múltiples

### Día 3:
**Miembro 1 (Jhosmar):**
- ¿Qué hice ayer? Completé CRUD de libros con relaciones autores/categorías
- ¿Qué haré hoy? Implementaré patrones Factory y Builder, mejoraré seguridad
- ¿Qué dificultades tuve? Error JSON "Unexpected token '<'" al crear autor — PHP devolvía HTML

### Día 4:
**Miembro 1 (Jhosmar):**
- ¿Qué hice ayer? Corregí errores JSON en APIs, implementé output buffering
- ¿Qué haré hoy? Agregaré session_regenerate_id, HttpOnly, SameSite, vista 403
- ¿Qué dificultades tuve? El método `logAction()` no existía en LoggerService

### Día 5:
**Miembro 1 (Jhosmar):**
- ¿Qué hice ayer? Completé seguridad de sesiones y control de acceso
- ¿Qué haré hoy? Documentación técnica, README, database.sql, Plan de Iteraciones
- ¿Qué dificultades tuve? Organizar toda la documentación según la rúbrica

---

## 1.12 Feedback de Stakeholders

### Resumen de comentarios recibidos:
- La interfaz del catálogo es intuitiva y visualmente atractiva
- El panel administrativo necesitaba mejor manejo de errores (corregido)
- Se sugirió agregar búsqueda más avanzada con filtros combinados
- Se solicitó que los toasts de error sean más descriptivos

### Plan de acción:
1. ✅ Mejorar manejo de errores en APIs (output buffering, JSON seguro)
2. ✅ Mensajes de error más descriptivos con excepciones personalizadas
3. 📋 Búsqueda avanzada → planificada para Sprint 3
4. 📋 Reportes exportables → planificada para Sprint 3

---

## 1.13 Retrospectiva de la Iteración

### Qué salió bien:
- La arquitectura en capas facilita agregar nuevas entidades sin afectar las existentes
- El patrón Repository permitió reutilizar código CRUD entre todas las entidades
- Las excepciones personalizadas proporcionan mensajes amigables al usuario
- El sistema de Toast notifications mejora la experiencia de usuario

### Qué se puede mejorar:
- Agregar pruebas automatizadas (PHPUnit)
- Implementar tokens CSRF en formularios
- Mejorar paginación en el catálogo público
- Agregar caché de consultas frecuentes

### Qué se hará en la siguiente iteración:
- Módulo de reseñas y calificaciones
- Tracking de descargas con estadísticas
- Búsqueda avanzada con filtros combinados
- Pruebas unitarias con PHPUnit

---

## 1.14 Entorno y Herramientas

| Componente | Herramienta |
|---|---|
| **IDE/Editor** | Visual Studio Code |
| **Modelado UML** | Draw.io / Mermaid |
| **Lenguaje de programación** | PHP 8.2, JavaScript ES6+, HTML5, CSS3 |
| **Framework** | PHP puro con arquitectura MVC propia |
| **Control de versiones** | Git + GitHub |
| **Gestión del trabajo** | Jira / Miro |
| **Estructura de datos usada** | Arrays asociativos PHP, FormData JS |
| **Servidor Web** | Apache (XAMPP 8.x) |
| **Gestor de Base de Datos** | MySQL 8.0 (phpMyAdmin) |
| **Patrones de Diseño** | MVC, Singleton, Repository, Factory, Builder |
