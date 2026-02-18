# Documentación - Alada Producciones

Sitio web de Alada Catering y Eventos. Incluye página pública, panel de administración y gestión de imágenes del portafolio.

---

## 1. Cómo acceder al proyecto

### Requisitos previos

- **PHP** 7.4 o superior (recomendado 8.x)
- Para producción: **Apache** con `mod_rewrite` habilitado

### Opción A: Servidor integrado de PHP (desarrollo)

```bash
cd ruta/aladaproducciones
php -S localhost:8000
```

- URL base: **http://localhost:8000**
- Acceso directo a la página principal: **http://localhost:8000/pages/index.php**

### Opción B: Apache (XAMPP, WAMP, Laragon)

1. Copia la carpeta `aladaproducciones` en `htdocs` (o la carpeta web configurada)
2. Inicia Apache
3. Accede a: **http://localhost/aladaproducciones**

Con Apache se aplican las reglas de `.htaccess` y funcionan las URLs limpias.

---

## 2. Mapa de rutas

### Rutas públicas

| URL | Destino | Descripción |
|-----|---------|-------------|
| `/` | `pages/index.php` | Página principal (Inicio, Servicios, Nosotros, Portafolio, Contacto) |
| `/pages/index.php` | Página principal | Redirige a `/` cuando se usa Apache |
| `/pages/test.html` | Página de pruebas | Acceso directo al archivo HTML de prueba |

### Rutas del panel de administración

| URL | Destino | Descripción |
|-----|---------|-------------|
| `/admin/login` o `/admin/login.php` | Formulario de login | Inicio de sesión del administrador |
| `/admin` o `/admin/admin.php` | Panel de administración | Gestión de carrusel, servicios y portafolio (requiere sesión) |
| `/admin/logout` o `/admin/logout.php` | Cierre de sesión | Cierra la sesión y redirige al login |

### Flujo de autenticación

```
/admin/login (no autenticado)
    ↓ [Login correcto]
/admin (panel principal)

/admin (sin sesión)
    ↓ [Redirección]
/admin/login

/admin/logout
    ↓ [Sesión destruida]
/admin/login
```

### Endpoints API (uso interno del panel)

> **Nota para Postman:** Todos los endpoints requieren sesión activa. Inicia sesión en `/admin/login.php` desde el navegador para obtener la cookie de sesión y luego en Postman usa "Send cookies automatically" o copia la cookie `ALADA_SESSION` manualmente.

---

#### 1. Subir imagen (carrusel o servicios)

| Propiedad | Valor |
|-----------|-------|
| **URL** | `http://localhost:8000/admin/upload.php` |
| **Método** | `POST` |
| **Content-Type** | `multipart/form-data` |

**Parámetros (form-data):**

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `image` | file | Sí | Archivo de imagen (JPG, PNG, GIF, WebP) |
| `type` | string | Sí | `carousel` o `service` |
| `index` | number | Solo si type=carousel | Índice 0, 1 o 2 (imágenes del carrusel) |
| `serviceType` | string | Solo si type=service | `wedding`, `catering`, `fotovideo` o `eventos` |

**Ejemplo respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Imagen actualizada correctamente",
  "path": "../img/foto portada/1.jpg?v=1707890123"
}
```

**Ejemplo respuesta error (200):**
```json
{
  "success": false,
  "message": "Tipo de archivo no permitido. Use JPG, PNG, GIF o WebP."
}
```

**Ejemplo respuesta sin sesión (403):**
```json
{
  "success": false,
  "message": "Acceso no autorizado"
}
```

---

#### 2. Obtener imágenes del portafolio

| Propiedad | Valor |
|-----------|-------|
| **URL** | `http://localhost:8000/admin/portfolio_manager.php?action=get&category=wedding` |
| **Método** | `GET` |

**Query params:**

| Parámetro | Tipo | Requerido | Valores | Descripción |
|-----------|------|-----------|---------|-------------|
| `action` | string | Sí | `get` | Acción a ejecutar |
| `category` | string | No | `wedding`, `eventos`, `catering`, `fotovideo` | Por defecto: `wedding` |

**Ejemplo respuesta exitosa (200):**
```json
{
  "success": true,
  "images": [
    {
      "id": 1,
      "path": "//localhost:8000/img/fotos wedding planner/1.jpg?v=1707890123",
      "category": "wedding"
    },
    {
      "id": 2,
      "path": "//localhost:8000/img/fotos wedding planner/10.jpg?v=1707890123",
      "category": "wedding"
    }
  ]
}
```

**Ejemplo respuesta vacía (200):**
```json
{
  "success": true,
  "images": []
}
```

---

#### 3. Subir imagen al portafolio

| Propiedad | Valor |
|-----------|-------|
| **URL** | `http://localhost:8000/admin/portfolio_manager.php?action=upload` |
| **Método** | `POST` |
| **Content-Type** | `multipart/form-data` |

**Parámetros (form-data):**

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `action` | string | Sí (query) | `upload` |
| `image` | file | Sí | Archivo de imagen (JPG, PNG, GIF, WebP) |
| `category` | string | Sí | `wedding`, `eventos`, `catering`, `fotovideo` |

**Ejemplo respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Imagen agregada correctamente",
  "image": {
    "id": 15,
    "path": "img/fotos wedding planner/15_1707890123.jpg",
    "category": "wedding"
  }
}
```

**Ejemplo respuesta error (200):**
```json
{
  "success": false,
  "message": "Categoría no válida"
}
```

---

#### 4. Eliminar imagen del portafolio

| Propiedad | Valor |
|-----------|-------|
| **URL** | `http://localhost:8000/admin/portfolio_manager.php?action=delete` |
| **Método** | `POST` |
| **Content-Type** | `application/x-www-form-urlencoded` o `multipart/form-data` |

**Parámetros (form):**

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `action` | string | Sí (query) | `delete` |
| `id` | number | Sí | ID de la imagen a eliminar |

**Ejemplo respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Imagen eliminada correctamente"
}
```

**Ejemplo respuesta error (200):**
```json
{
  "success": false,
  "message": "ID de imagen no válido"
}
```

---

#### 5. Actualizar lista del portafolio

| Propiedad | Valor |
|-----------|-------|
| **URL** | `http://localhost:8000/admin/portfolio_manager.php?action=refresh` |
| **Método** | `GET` |

**Ejemplo respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Lista de imágenes actualizada correctamente",
  "count": 42
}
```

---

#### 6. Debug (información de depuración)

| Propiedad | Valor |
|-----------|-------|
| **URL** | `http://localhost:8000/admin/portfolio_manager.php?action=debug` |
| **Método** | `GET` |

**Ejemplo respuesta (200):**
```json
{
  "success": true,
  "baseUrl": "//localhost:8000",
  "basePath": "C:/ruta/aladaproducciones",
  "portfolioFile": "C:/ruta/aladaproducciones/data/portfolio.json",
  "portfolioFileExists": true,
  "categoryPaths": { ... },
  "categoryUrls": { ... },
  "directoryExists": { "wedding": true, "eventos": true, ... },
  "fileCount": { "wedding": 18, "eventos": 12, ... }
}
```

---

## 3. Panel de administrador

### Acceso

- **URL:** `http://localhost:8000/admin/login` (o `.../admin/login.php`)
- **Usuario:** `admin`
- **Contraseña:** `alada2025`

> Para cambiar la contraseña, copia `config/config.example.php` a `config/config.local.php` y genera un nuevo hash con:
> ```bash
> php -r "echo password_hash('tu_contraseña', PASSWORD_DEFAULT);"
> ```

### Funcionalidades del panel

1. **Carrusel** – Editar las 3 imágenes de la portada
2. **Servicios** – Cambiar imágenes de Wedding Planner, Catering, Fotografía y Video, Coffee Break
3. **Portafolio** – Subir, eliminar y filtrar imágenes por categoría:
   - Wedding Planner
   - Coffe Break
   - Catering
   - Fotografía y Video

### Cierre de sesión

- Botón “Cerrar Sesión” en el panel, o
- Ir directamente a: **http://localhost:8000/admin/logout**

---

## 4. Estructura de carpetas

```
aladaproducciones/
├── index.php              # Redirige / hacia pages/index.php
├── .htaccess              # Reglas de reescritura (Apache)
├── config/                # Configuración (credenciales, sesión, CSRF)
│   ├── config.php
│   └── config.example.php
├── admin/                 # Panel de administración
│   ├── login.php          # Inicio de sesión
│   ├── admin.php          # Panel principal
│   ├── logout.php         # Cierre de sesión
│   ├── upload.php         # API subida de imágenes
│   ├── portfolio_manager.php  # API gestión portafolio
│   └── includes/
│       ├── init.php       # Inicialización (sesión, config)
│       └── auth.php       # Verificación de autenticación
├── pages/                 # Páginas públicas
│   ├── index.php          # Página principal
│   └── test.html          # Página de pruebas
├── assets/                # CSS, JS, audio
├── img/                   # Imágenes del sitio
├── data/                  # Datos (portfolio.json)
└── backups/               # Copias de seguridad (ignorado por git)
```

---

## 5. URLs limpias (solo con Apache)

Con Apache y `.htaccess` activo:

- `/admin/login.php` → `/admin/login`
- `/admin/admin.php` → `/admin`
- `/pages/index.php` → `/`
- Extensiones `.php` visibles en la URL se redirigen a la versión limpia

Con el servidor PHP integrado (`php -S`), las URLs se usan con extensión `.php`.
