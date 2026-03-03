# Sistema de Biblioteca Digital — BiblioDigital

## Descripción Breve
BiblioDigital es un sistema web de gestión de biblioteca digital desarrollado con PHP 8.2, MySQL y arquitectura MVC. Permite administrar libros, autores, categorías y editoriales con un panel de administración completo y un catálogo público para usuarios.

## Problema que Resuelve
La gestión manual de bibliotecas digitales es ineficiente: no permite búsquedas rápidas, el control de inventario es propenso a errores, y no existe trazabilidad de acciones. BiblioDigital centraliza la administración del acervo bibliográfico con control de acceso, registro de actividades (logs), y una interfaz moderna para consulta del catálogo.

## Usuarios Objetivo
- **Administradores de biblioteca**: Gestión completa de libros, autores, categorías y editoriales
- **Usuarios/Lectores**: Consulta del catálogo, búsqueda de libros, descarga de materiales
- **Instituciones educativas**: Control de acervo bibliográfico digital

## Tecnologías Utilizadas
| Tecnología | Versión | Uso |
|---|---|---|
| PHP | 8.2+ | Backend / Lógica de negocio |
| MySQL | 8.0+ | Base de datos relacional |
| HTML5/CSS3/JS | ES6+ | Frontend |
| Composer | 2.x | Autoload PSR-4 |
| XAMPP | 8.x | Servidor local |
| Git/GitHub | - | Control de versiones |

## Estructura del Proyecto
```
biblio/
├── config/               # Configuración (DB, constantes)
├── public/               # Archivos accesibles al navegador
│   ├── admin/            # Panel de administración
│   ├── api/              # Endpoints JSON (AJAX)
│   ├── css/              # Estilos
│   ├── js/               # JavaScript cliente
│   └── includes/         # Header/Footer compartidos
├── src/                  # Código fuente PHP (PSR-4)
│   ├── Builders/         # Patrón Builder
│   ├── Enums/            # Enumeraciones (BookStatus, UserRole)
│   ├── Exceptions/       # Excepciones personalizadas
│   ├── Factory/          # Patrón Factory
│   ├── Models/           # Modelos de datos
│   ├── Repositories/     # Capa de acceso a datos
│   ├── Services/         # Lógica de negocio
│   └── Utils/            # Helpers y Validador
├── composer.json         # Autoload y dependencias
└── database.sql          # Script de base de datos
```

## Instalación
1. Clonar el repositorio en `htdocs/biblio`
2. Importar `database.sql` en MySQL
3. Ejecutar `composer install` (o `composer dump-autoload`)
4. Configurar `config/DatabaseConnection.php` con credenciales de BD
5. Acceder a `http://localhost/biblio/public/`

## Patrones de Diseño Implementados
- **MVC** (Model-View-Controller) — Separación por capas
- **Repository** — Abstracción de acceso a datos
- **Singleton** — Conexión a base de datos (`DatabaseConnection`)
- **Factory** — Creación de materiales (`MaterialFactory.js`)
- **Builder** — Construcción de libros (`bookBuilder.js`)

## Seguridad
- `password_hash()` / `password_verify()` para contraseñas
- `session_regenerate_id()` para prevenir Session Fixation
- Cookies HttpOnly, SameSite=Lax, Secure (HTTPS)
- Control de expiración de sesión (30 minutos)
- Protección por roles (Admin / Usuario)
- Vista 403 para acceso denegado
- Validación server-side con clase `Validator`
- Prepared Statements PDO (anti SQL Injection)
