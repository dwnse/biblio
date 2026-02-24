# 📚 BiblioDigital — Estructura del Proyecto

```
biblio/
├── 📁 config/                          # Configuración de la aplicación
│   ├── constants.php                   # Constantes globales (rutas, entorno, sesión)
│   └── DatabaseConnection.php          # Singleton de conexión PDO a MySQL
│
├── 📁 src/                             # Código fuente principal (namespace App\)
│   ├── 📁 Controllers/                 # (vacío — lógica en archivos API)
│   │
│   ├── 📁 Enums/                       # Enumeraciones del sistema
│   │   ├── BookStatus.php              # Estados de un libro
│   │   ├── UserRole.php                # Roles de usuario
│   │   └── UserStatus.php              # Estados de usuario
│   │
│   ├── 📁 Exceptions/                  # Excepciones personalizadas
│   │   ├── BaseException.php           # Excepción base del proyecto
│   │   ├── AuthenticationException.php # Error de autenticación
│   │   ├── BookNotFoundException.php   # Libro no encontrado
│   │   └── UserNotFoundException.php   # Usuario no encontrado
│   │
│   ├── 📁 Models/                      # Modelos de datos
│   │   ├── Author.php                  # Modelo de Autor
│   │   ├── Book.php                    # Modelo de Libro
│   │   ├── Category.php                # Modelo de Categoría
│   │   ├── Editorial.php               # Modelo de Editorial
│   │   └── User.php                    # Modelo de Usuario
│   │
│   ├── 📁 Repositories/               # Repositorios (acceso a datos)
│   │   ├── IRepository.php             # Interfaz base del repositorio
│   │   ├── BaseRepository.php          # Repositorio base con CRUD genérico
│   │   ├── AuthorRepository.php        # Repositorio de Autores
│   │   ├── BookRepository.php          # Repositorio de Libros
│   │   ├── CategoryRepository.php      # Repositorio de Categorías
│   │   ├── EditorialRepository.php     # Repositorio de Editoriales
│   │   └── UserRepository.php          # Repositorio de Usuarios
│   │
│   ├── 📁 Services/                    # Capa de lógica de negocio
│   │   ├── BookService.php             # Servicio de Libros
│   │   ├── LoggerService.php           # Servicio de Logging
│   │   └── UserService.php             # Servicio de Usuarios (registro/login)
│   │
│   ├── 📁 Utils/                       # Utilidades y helpers
│   │   ├── Helpers.php                 # Funciones auxiliares (sanitización, flash, etc.)
│   │   └── Validator.php               # Validador de formularios
│   │
│   └── 📁 Views/                       # (vacío — vistas en public/)
│
├── 📁 public/                          # Raíz pública del servidor web
│   ├── index.php                       # Página principal (redirige)
│   ├── login.php                       # Página de inicio de sesión
│   ├── register.php                    # Página de registro
│   ├── catalogo.php                    # Catálogo de libros
│   ├── libro.php                       # Detalle de un libro
│   │
│   ├── 📁 admin/                       # Panel de administración
│   │   ├── index.php                   # Dashboard del admin
│   │   ├── libros.php                  # Gestión de libros
│   │   └── libro_form.php              # Formulario de crear/editar libro
│   │
│   ├── 📁 api/                         # Endpoints API (JSON)
│   │   ├── auth.php                    # Autenticación (login/register)
│   │   ├── books.php                   # CRUD de libros
│   │   └── logout.php                  # Cerrar sesión
│   │
│   ├── 📁 css/                         # Estilos
│   │   └── styles.css                  # Hoja de estilos principal
│   │
│   ├── 📁 js/                          # JavaScript
│   │   └── app.js                      # Lógica del frontend
│   │
│   └── 📁 includes/                    # Componentes reutilizables de las vistas
│       ├── header.php                  # Cabecera (navbar, meta, CSS)
│       └── footer.php                  # Pie de página
│
├── 📁 tests/                           # Tests (vacío)
├── 📁 vendor/                          # Dependencias Composer (autoload PSR-4)
│
├── composer.json                       # Configuración de Composer y autoload
├── composer.lock                       # Versiones exactas de dependencias
├── package.json                        # Dependencias npm (bcryptjs)
├── bd.txt                              # Script SQL de la base de datos
├── prompt.txt                          # Prompt / especificación del proyecto
├── README.md                           # Documentación del proyecto
└── .gitignore                          # Archivos ignorados por Git
```

## 🏗️ Arquitectura

El proyecto sigue una arquitectura **MVC en capas** con patrón **Repository**:

```
┌──────────────────────────────────────────────────┐
│              public/ (Vista + API)                │
│   login.php, register.php, catalogo.php, api/*   │
└──────────────────┬───────────────────────────────┘
                   │
┌──────────────────▼───────────────────────────────┐
│              Services (Lógica de negocio)         │
│       UserService, BookService, LoggerService     │
└──────────────────┬───────────────────────────────┘
                   │
┌──────────────────▼───────────────────────────────┐
│            Repositories (Acceso a datos)          │
│   BaseRepository → UserRepo, BookRepo, etc.      │
└──────────────────┬───────────────────────────────┘
                   │
┌──────────────────▼───────────────────────────────┐
│         Config / DatabaseConnection (PDO)         │
│              Singleton → MySQL                    │
└──────────────────────────────────────────────────┘
```

## 🔧 Tecnologías

| Componente   | Tecnología                  |
|--------------|-----------------------------|
| Backend      | PHP 8.2+                    |
| Base de datos| MySQL (via PDO)             |
| Frontend     | HTML, CSS, JavaScript       |
| Autoload     | Composer PSR-4              |
| Servidor     | Apache (XAMPP)              |
