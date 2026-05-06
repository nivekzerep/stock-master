# StockMaster - Sistema de Inventario y Pedidos 

StockMaster es una aplicación web desarrollada en Laravel para la gestión eficiente de un catálogo de productos, categorías y la simulación de órdenes de pedido en tiempo real.

## Características Principales

- **Autenticación Segura:** Sistema de Login y Registro utilizando Laravel Breeze.
- **Gestión de Inventario (CRUD):** Creación, lectura, actualización y eliminación de Productos y Categorías.
- **Validaciones de Negocio:** Prevención de stock negativo y validación de SKU únicos.
- **Búsqueda y Filtros Dinámicos:** Buscador por nombre/SKU, filtrado por categorías y ordenamiento ascendente/descendente (protegido contra inyección SQL).
- **Módulo de Pedidos Transaccional:** Uso de `DB::transaction` para garantizar la deducción exacta del stock y la integridad de los datos.
- **Generación de PDF:** Exportación de comprobantes de órdenes al instante utilizando DomPDF.
- **Optimización de consultas:** Implementación de *Eager Loading* para prevenir problemas de rendimiento (N+1 queries).
- **Localización Automática:** Interfaz, validaciones y formato de fechas configurados 100% en español.

## Requisitos Previos

Para ejecutar este proyecto en un entorno local, se requiere lo siguiente:

- PHP >= 8.2
- Composer
- Node.js y NPM
- MySQL (o MariaDB)

---

## Instrucciones paso a paso para ejecutar el proyecto

Sigue estas instrucciones estrictamente en orden para inicializar el proyecto sin errores.

### 1. Clonar el repositorio
Abre tu terminal y ejecuta:
```bash
git clone https://github.com/nivekzerep/stock-master.git
cd stock-master
```

### 2. Instalar dependencias (Backend y Frontend)
Instala los paquetes de PHP y JavaScript necesarios:
```bash
composer install
npm install
```

### 3. Compilar los assets del Frontend (Tailwind/Vite)
Es obligatorio compilar los estilos para que la interfaz se visualice correctamente:
```bash
npm run build
```

### 4. Configurar el entorno y Base de Datos
Copia el archivo de ejemplo para generar tus variables de entorno locales:
```bash
cp .env.example .env
```

Genera la llave de encriptación de la aplicación:
```bash
php artisan key:generate
```

Abre tu gestor de base de datos (MySQL Workbench, phpMyAdmin, DBeaver, etc.) y crea una base de datos vacía llamada `stock_master`. Luego, abre el archivo `.env` en la raíz del proyecto y verifica que tus credenciales coincidan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_master
DB_USERNAME=root      # <- Cambia por tu usuario de MySQL
DB_PASSWORD=          # <- Cambia por tu contraseña de MySQL
```

### 5. Ejecutar Migraciones
Una vez configurado el `.env` y creada la BD, construye las tablas relacionales:
```bash
php artisan migrate
```
*(Nota: Este proyecto no requiere Seeders. Puedes registrar un usuario nuevo directamente en la interfaz).*

### 6. Levantar el servidor
Finalmente, inicia el servidor local de Laravel:
```bash
php artisan serve
```

La aplicación estará lista y funcionando en: [http://localhost:8000](http://localhost:8000)

Para comenzar a probar el sistema, dirígete a la ruta principal, haz clic en **"Registrarse"** en la esquina superior derecha, crea un usuario y tendrás acceso al dashboard del inventario.
