# 🐾 API Personas y Mascotas

API RESTful desarrollada en Laravel con autenticación JWT, documentación Swagger, arquitectura en capas (Service, Resource, Request), y pruebas de integración.

---

## ⚙️ Requisitos del sistema

- PHP >= 8.1
- Composer
- Laravel >= 8.x
- MySQL
- Extensiones PHP: `openssl`, `pdo`, `mbstring`, `bcmath`, `xml`, `tokenizer`

---

## 🚀 Instalación y ejecución

```bash
git clone https://github.com/JFernandoObando/api-personas-mascotas.git
cd api-personas-mascotas

composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

---

## 🐘 Crear la base de datos MySQL antes de configurar `.env`

Antes de ejecutar las migraciones, asegúrate de crear una base de datos en tu servidor MySQL. Puedes hacerlo de varias maneras:

### 🔧 Opción 1: Usando MySQL en consola

1. Abre una terminal o consola MySQL:
   ```bash
   mysql -u root -p


CREATE DATABASE PruebaMascota CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

---

## 🛠 Configura tu `.env` con las credenciales de base de datos

Asegúrate de tener estas variables en tu archivo `.env` para que Laravel pueda conectarse correctamente a tu base de datos MySQL local.

### 🧾 Ejemplo:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PruebaMascota
DB_USERNAME=root
DB_PASSWORD=root

---

## ⚠️ IMPORTANTE

Al final del archivo `.env`, **debes agregar** la siguiente variable de entorno para que la API externa TheDogAPI funcione correctamente:

```env
DOG_API_BASE_URL=https://api.thedogapi.com/v1


--

php artisan migrate --seed
php artisan l5-swagger:generate

php artisan serve
```

---

## 🔐 Autenticación

Usamos JWT (JSON Web Token).

### 📥 Login

```http
POST /api/login
```

**Body:**
```json
{
  "email": "admin@example.com",
  "password": "admin1234"
}
```

**Respuesta:**
```json
{
  "message": "Inicio de sesión correcto",
  "user": {
    "name": "Admin",
    "email": "admin@example.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhb..."
}
```


### 🔐 Cómo usar el token JWT (Bearer Token)

Este `token` debe enviarse en todas las peticiones protegidas dentro del encabezado `Authorization` con el prefijo `Bearer`:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Ejemplo con `curl`:**

```bash
curl -X GET http://localhost:8000/api/personas   -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhb..."
```

---
### 🔐 Registro

```http
POST /api/register
```

**Body:**
```json
{
  "name": "Nuevo Usuario",
  "email": "nuevo@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```


## 📚 Documentación Swagger

Disponible en:

```
http://localhost:8000/api/documentation
```

---

## 👤 Usuario de prueba

```json
{
  "email": "admin@example.com",
  "password": "admin1234"
}
```

Creado automáticamente con los seeders.

---

## 📌 Endpoints y ejemplos

### 🧑 Personas

#### ➕ Crear persona

```http
POST /api/personas
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nombre": "Juan Pérez",
  "email": "juan@example.com",
  "fecha_nacimiento": "1990-05-15",
  "user_id": 1
}
```

#### 📋 Listar personas

```http
GET /api/personas
Authorization: Bearer {token}
```

#### 👁 Ver una persona

```http
GET /api/personas/{id}
Authorization: Bearer {token}
```

#### ✏️ Actualizar persona

```http
PUT /api/personas/{id}
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nombre": "Juan P. Actualizado",
  "email": "juan_actualizado@example.com",
  "fecha_nacimiento": "1985-10-10"
}
```

#### ❌ Eliminar persona

```http
DELETE /api/personas/{id}
Authorization: Bearer {token}
```

#### 🐾 Ver mascotas de una persona

```http
GET /api/personas/{id}/mascotas
Authorization: Bearer {token}
```

---

### 🐶 Mascotas

#### ➕ Crear mascota

```http
POST /api/mascotas
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nombre": "AVE",
  "especie": "Perro",
  "raza": "Labrador",
  "edad": 4,
  "persona_id": 1
}
#Los otros campos se llenan consumiendo la API externa
```

#### 📋 Listar mascotas

```http
GET /api/mascotas
Authorization: Bearer {token}
```

#### 👁 Ver mascota

```http
GET /api/mascotas/{id}
Authorization: Bearer {token}
```

#### ✏️ Actualizar mascota

```http
PUT /api/mascotas/{id}
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nombre": "Firulais Actualizado",
  "especie": "Perro",
  "raza": "Golden Retriever",
  "edad": 5,
  "imagen_url": "https://example.com/foto2.jpg",
  "temperamento": "Juguetón",
  "anios_vida": 13,
  "descripcion": "Muy activo.",
  "persona_id": 1
}
```

#### ❌ Eliminar mascota

```http
DELETE /api/mascotas/{id}
Authorization: Bearer {token}
```

--

## 🧠 Consideraciones del desarrollador

- Arquitectura limpia usando `Service`, `FormRequest`, `Resource`, `Policy`.
- JWT Token guardado en la base (`users.jwt_token`).
- Documentación generada con anotaciones Swagger `@OA`.
- Pruebas en `tests/Feature/`.
- SoftDeletes para registros.
- Se implementan **Policies** en el modelo `Persona`, lo que limita el acceso a ciertos recursos. Solo el usuario dueño del registro (`user_id`) tiene permiso para ver, actualizar o eliminar sus datos.
  - Estas políticas están configuradas en `PersonaPolicy` y aplicadas automáticamente mediante `authorizeResource(Persona::class, 'persona')` en el controlador.

> ⚠️ **IMPORTANTE**: Para probar este comportamiento correctamente:
>
> 1. Autentícate con un usuario y conserva su token JWT.
> 2. Crea una persona con ese usuario autenticado.
> 3. Luego, intenta acceder a esa persona con otro usuario distinto (usando su propio token).
> 4. Se generará un error `403 Forbidden`, ya que solo el propietario puede acceder/modificar ese recurso.

--
## 🧪 Comandos útiles

```bash
php artisan test                     # Ejecutar pruebas
php artisan migrate:fresh --seed    # Resetear base de datos
php artisan l5-swagger:generate     # Regenerar documentación
```
