### 🛂 **Sales API **  

Este es un **API RESTful** para la gestión de ventas y productos, desarrollado con **Laravel**. Permite registrar ventas, administrar productos, implementación de roles (admin y user) y generar reportes filtrados por fecha.  

---

## 🚀 **Tecnologías utilizadas**  
- **Laravel 10** (Framework PHP)  
- **MySQL** (Base de datos)  
- **PHP 8+**  
- **Composer** (Gestor de dependencias)  
- **Postman** (Para pruebas de API)  

---

## 📂 **Instalación**  
1⃣ **Clonar el repositorio:**  
```sh
git clone https://github.com/tuusuario/tu-repo.git
cd tu-repo
```
  
2⃣ **Instalar dependencias:**  
```sh
composer install
```

3⃣ **Configurar variables de entorno:**  
```sh
cp .env.example .env
php artisan key:generate
```
- Edita `.env` con tus credenciales de base de datos:  
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=nombre_de_tu_bd
  DB_USERNAME=tu_usuario
  DB_PASSWORD=tu_contraseña
  ```

4⃣ **Ejecutar migraciones y seeders:**  
```sh
php artisan migrate --seed
```
*(Esto creará las tablas y algunos datos iniciales).*

5⃣ **Levantar el servidor:**  
```sh
php artisan serve
```
  
---

## 🔥 **Endpoints Disponibles**  

### 📌 **Login y Registro (público)**  
| Método | Endpoint | Descripción |
|--------|---------|-------------|
| `POST` | `/api/register` | Registro en la aplicación |
| `POST` | `/api/login` | Iniciar sesión en la aplicación |

### 📌 **Productos**  
| Método | Endpoint | Descripción |
|--------|---------|-------------|
| `GET` | `/api/allproducts` | Lista todos los productos |
| `GET` | `/api/products/{id}` | Lista el producto por ID |
| `POST` | `/api/products` | Crea un producto |
| `PATCH` | `/api/products/{id}` | Actualizar un producto por ID |
| `DELETE` | `/api/products/{id}` | Desactivar un producto por ID |

### 📌 **Ventas**  
| Método | Endpoint | Descripción |
|--------|---------|-------------|
| `POST` | `/api/sales` | Registrar una venta |
| `GET` | `/api/reports/sales?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD` | Generar un reporte filtrado por fecha |

## 🐝 **Imágen de BD**  
link: https://i.ibb.co/nq3yVfSG/Whats-App-Image-2025-03-06-at-5-22-18-PM.jpg

