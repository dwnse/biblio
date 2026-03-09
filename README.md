# BiblioDigital - Sistema de Biblioteca Digital

## 1. Descripción general del sistema

### Nombre del sistema
BiblioDigital

### Problema que busca resolver
El sistema resuelve la gestión ineficiente de bibliotecas digitales mediante la centralización de la administración de acervos bibliográficos, permitiendo búsquedas rápidas, control de inventario preciso, trazabilidad de acciones y una interfaz moderna para la consulta del catálogo.

### Usuarios del sistema
- **Administradores**: Gestión completa de libros, autores, categorías, editoriales y usuarios
- **Usuarios registrados**: Consulta del catálogo, búsqueda, reseñas y descargas
- **Instituciones educativas**: Control y acceso a materiales digitales

### Funcionalidades principales implementadas
- Gestión completa de libros con autores, categorías y editoriales
- Sistema de autenticación con roles (Admin/Usuario)
- Catálogo público con búsqueda y filtrado
- Sistema de reseñas y calificaciones
- Panel de administración
- Logs de auditoría
- API REST para integración

### Arquitectura utilizada
Cliente-Servidor con API REST. Backend en Laravel (PHP), Frontend en Vue.js, Base de datos MySQL.

## 2. Arquitectura del sistema

### Comunicación frontend-backend
El frontend (Vue.js) se comunica con el backend (Laravel) mediante peticiones HTTP a la API REST. Los datos se intercambian en formato JSON.

### Uso de HTTP y JSON
- **GET**: Para obtener datos (listar libros, obtener usuario)
- **POST**: Para crear recursos (registro, login, crear libro)
- **PUT/PATCH**: Para actualizar recursos
- **DELETE**: Para eliminar recursos
- Todas las respuestas de la API están en formato JSON

### Flujo de datos
1. Usuario interactúa con Vue.js
2. Vue.js hace petición AJAX con Axios a endpoints Laravel
3. Laravel procesa la petición, consulta MySQL
4. Respuesta JSON retorna a Vue.js
5. Vue.js actualiza la interfaz

## 3. Estructura del proyecto

### Backend (Laravel)
```
app/
├── Http/Controllers/Api/     # Controladores API
├── Models/                   # Modelos Eloquent
├── Services/                 # Lógica de negocio
├── Repositories/             # Acceso a datos
config/                       # Configuraciones
database/
├── migrations/               # Migraciones BD
├── seeders/                  # Datos iniciales
routes/
├── api.php                   # Rutas API
```

#### Modelos
Los modelos Eloquent representan las entidades de la BD con relaciones:
- `Usuario`: Autenticación, roles
- `Libro`: Libros con relaciones many-to-many
- `Autor`, `Categoria`, `Editorial`: Entidades relacionadas

#### Controladores
Controladores API manejan las peticiones HTTP:
- `AuthController`: Login, registro, logout
- `BookController`: CRUD de libros
- `CatalogController`: Búsqueda y filtrado

#### Rutas
- `/api/register` (POST)
- `/api/login` (POST)
- `/api/books` (GET, POST)
- `/api/books/{id}` (GET, PUT, DELETE)

#### Middleware
- `auth:sanctum`: Protección de rutas con tokens
- Validación de roles para acceso admin

#### Acceso a base de datos
- Eloquent ORM para consultas
- Migraciones para esquema de BD
- Seeders para datos iniciales

### Frontend (Vue.js)
```
frontend/
├── src/
│   ├── views/          # Páginas principales
│   ├── components/     # Componentes reutilizables
│   ├── router/         # Configuración de rutas
│   ├── stores/         # Estado con Pinia
│   └── services/       # Llamadas a API
```

#### Componentes Vue
- `LoginView`: Formulario de login
- `RegisterView`: Formulario de registro
- `CatalogView`: Listado y búsqueda de libros
- `BookDetail`: Vista detallada de libro

#### Consumo de API
Uso de Axios para llamadas HTTP con interceptores para tokens de autenticación.

#### Organización del frontend
- **Views**: Páginas principales
- **Components**: Elementos reutilizables
- **Router**: Navegación SPA
- **Stores**: Estado global (autenticación, libros)

## 4. Diseño de base de datos

### Tablas principales
- **usuarios**: Información de usuarios y roles
- **libros**: Metadatos de libros
- **autores**: Información de autores
- **categorias**: Clasificación de libros
- **editorial**: Información de editoriales
- **roles**: Definición de roles

### Claves primarias y foráneas
- PK: id_usuario, id_libro, id_autor, etc.
- FK: id_rol → roles, id_editorial → editorial

### Relaciones
- Usuarios ↔ Roles (1:N)
- Libros ↔ Autores (N:M via libro_autor)
- Libros ↔ Categorías (N:M via libro_categoria)
- Libros ↔ Editorial (N:1)

## 5. Implementación de la API REST

### Endpoints disponibles

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/register` | Registrar nuevo usuario |
| POST | `/api/login` | Autenticar usuario |
| GET | `/api/user` | Obtener datos del usuario autenticado |
| POST | `/api/logout` | Cerrar sesión |
| GET | `/api/books` | Listar libros con filtros |
| POST | `/api/books` | Crear nuevo libro |
| GET | `/api/books/{id}` | Obtener libro específico |
| PUT | `/api/books/{id}` | Actualizar libro |
| DELETE | `/api/books/{id}` | Eliminar libro |

### Estructura de respuestas JSON
```json
{
  "data": [...],
  "message": "Operación exitosa",
  "errors": null
}
```

### Lógica en controladores
Los controladores validan entrada, interactúan con servicios/repositorios, manejan errores y retornan respuestas JSON estandarizadas.

## 6. Seguridad y autenticación

### Sistema de autenticación
- **Registro**: Validación de email único, hash de contraseña
- **Login**: Verificación de credenciales, generación de token
- **Bearer Token**: Laravel Sanctum para autenticación stateless

### Protección de rutas
```php
Route::middleware('auth:sanctum')->group(function () {
    // Rutas protegidas
});
```

### Almacenamiento de contraseñas
Uso de `Hash::make()` con bcrypt para hashear contraseñas.

### Medidas de seguridad
- **SQL Injection**: Prepared statements via Eloquent
- **CSRF**: Tokens en formularios
- **Sesiones seguras**: HttpOnly, SameSite
- **Validación**: Reglas estrictas en controladores

## 7. Implementación del Frontend con Vue

### Componentes desarrollados
- **LoginView**: Formulario con validación
- **RegisterView**: Registro de usuarios
- **CatalogView**: Grid de libros con búsqueda
- **BookDetail**: Vista detallada con reseñas

### Vistas principales
- Home: Bienvenida
- Login/Register: Autenticación
- Catalog: Navegación del catálogo

### Interacción con el usuario
- Formularios reactivos
- Estados de carga
- Mensajes de error/success
- Navegación SPA

### Consumo de la API
```javascript
const response = await axios.get('/api/books', {
  headers: { Authorization: `Bearer ${token}` }
});
```

## 8. Consumo de API mediante AJAX

### Tecnologías utilizadas
- **Axios**: Cliente HTTP para Vue.js
- **Interceptors**: Para agregar tokens automáticamente

### Peticiones GET
```javascript
axios.get('/api/books', { params: { q: 'search' } })
  .then(response => this.books = response.data);
```

### Peticiones POST
```javascript
axios.post('/api/login', credentials)
  .then(response => {
    localStorage.setItem('token', response.data.token);
  });
```

### Autenticación con token
```javascript
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
```

## 9. Pruebas con Postman

### Colección de pruebas
- **Auth**: Login, Register, Logout
- **Books**: CRUD operations
- **Catalog**: Search, Filter

### Autenticación con token
Headers: `Authorization: Bearer {token}`

### Endpoints probados
- POST /api/login
- GET /api/books
- POST /api/books
- PUT /api/books/{id}
- DELETE /api/books/{id}

## 10. Patrones de diseño

### Repository Pattern
**Problema resuelto**: Separa lógica de acceso a datos de la lógica de negocio.

**Implementación**:
```php
interface BookRepositoryInterface {
    public function findById($id);
    public function search($query);
}

class BookRepository implements BookRepositoryInterface {
    public function findById($id) {
        return Libro::with('autores')->find($id);
    }
}
```

**Beneficio**: Código más mantenible, fácil testing.

### Service Layer
**Problema resuelto**: Centraliza lógica de negocio compleja.

**Implementación**:
```php
class BookService {
    public function createBook(array $data) {
        // Validación y lógica de negocio
        $book = $this->bookRepository->create($data);
        $this->logger->log('Book created');
        return $book;
    }
}
```

## 11. Evidencias de funcionamiento

### API funcionando
- Endpoints responden correctamente con datos JSON
- Autenticación funciona con tokens
- CRUD operations validadas

### Pruebas con Postman
- Colección completa de requests
- Tests automatizados para responses
- Documentación generada

### Frontend
- Interfaz responsive
- Navegación funcional
- Integración completa con API

## 12. Consumo de API externa (opcional)

### API consumida: JSONPlaceholder
**Endpoint**: `https://jsonplaceholder.typicode.com/posts`

**Datos obtenidos**: Posts de ejemplo para demostración

**Integración**: Mostrados en vista de recomendaciones como contenido adicional

---

## Instalación y Configuración

### Requisitos
- PHP 8.2+
- Composer
- MySQL
- Node.js (para frontend)

### Pasos
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `php artisan db:seed`
6. `cd frontend && npm install && npm run dev`

### Ejecutar
- Backend: `php artisan serve`
- Frontend: `npm run dev` en frontend/
- BD: Importar bd.txt o ejecutar migraciones

### Datos de Prueba Incluidos

La base de datos incluye datos de prueba para todas las tablas:

#### Usuarios de Prueba
- **Admin**: admin@bibliodigital.com / admin123 (Rol: Administrador)
- **Usuario**: juan@example.com / user123 (Rol: Usuario)

#### Contenido de Prueba
- **4 Libros**: Cien años de soledad, Harry Potter, It, La casa de los espíritus
- **4 Autores**: García Márquez, Allende, King, Rowling
- **4 Categorías**: Ficción, No Ficción, Ciencia, Historia
- **3 Editoriales**: Planeta, Penguin, Santillana
- **2 Reseñas**: Calificaciones y comentarios de ejemplo
- **1 Descarga**: Registro de descarga de ejemplo
- **1 Log**: Registro de actividad
- **1 Recomendación**: Sugerencia de libro
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
