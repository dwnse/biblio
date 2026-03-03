# Plan de Iteraciones — BiblioDigital

## Iteración 1 (Sprint 1) — Fundación del Sistema
**Duración:** 1 semana  
**Objetivo:** Establecer la base del proyecto, arquitectura, base de datos y CRUD principal.

### Historias de Usuario Incluidas:
| ID | Historia | Prioridad | Estado |
|---|---|---|---|
| HU-01 | Registro Simplificado de Usuarios | Alta | Completada |
| HU-02 | Acceso de Invitados al Catálogo | Alta | Completada |
| HU-04 | Panel de Control Administrativo | Media | Completada |
| HU-09 | Doble Capa de Validación | Crítica | Completada |

### Entregable:
- Sistema base funcional con login/registro, catálogo público y panel admin
- Base de datos normalizada (3FN) con 10 tablas
- Arquitectura MVC con Composer PSR-4 autoload

---

## Iteración 2 (Sprint 2) — CRUD Completo + Seguridad + Patrones
**Duración:** 1 semana  
**Objetivo:** Completar todos los módulos CRUD, implementar patrones de diseño, reforzar seguridad de sesiones y manejo de errores.

### Historias de Usuario Incluidas:
| ID | Historia | Prioridad | Estado |
|---|---|---|---|
| HU-03 | Restricción de Descarga de Libros | Alta | Completada |
| HU-05 | Gestión de Autores (CRUD) | Alta | Completada |
| HU-06 | Gestión de Categorías (CRUD) | Media | Completada |
| HU-07 | Gestión de Editoriales (CRUD) | Alta | Completada |
| HU-08 | Gestión Central de Libros | Alta | Completada |

### Entregable:
- CRUD completo de Autores, Categorías, Editoriales y Libros
- Patrones Factory y Builder implementados
- Seguridad de sesiones mejorada (HttpOnly, SameSite, session_regenerate_id)
- Vista 403 para control de acceso
- Manejo de excepciones personalizadas con logging
- Validación doble capa (cliente + servidor)

---

## Iteración 3 (Sprint 3) — Futuro
**Objetivo:** Reseñas, descargas con tracking, búsqueda avanzada, reportes.

### Historias pendientes:
- Módulo de reseñas/calificaciones
- Tracking de descargas
- Búsqueda avanzada con filtros múltiples
- Reportes/estadísticas exportables
- Paginación avanzada del catálogo
