# Special One Rexville Dash 🚗

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
<a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2%2B-blue.svg" alt="PHP Version"></a>
</p>

## 🚀 Acerca del Proyecto

**Special One Rexville Dash** es una aplicación web completa para la gestión de servicios vehiculares desarrollada con Laravel 12. El sistema permite administrar clientes, vehículos, servicios y promociones, proporcionando una interfaz intuitiva para talleres mecánicos y centros de servicio automotriz.

### ✨ Características Principales

- **👥 Gestión de Clientes**: Registro y administración completa de clientes
- **🚗 Gestión de Vehículos**: Control detallado de vehículos con información técnica
- **🔧 Gestión de Servicios**: Registro y seguimiento de servicios realizados
- **👨‍💼 Panel de Administración**: Sistema de administradores con diferentes niveles de acceso
- **🎯 Sistema de Promociones**: Gestión de ofertas y promociones con fechas de validez
- **🔐 Autenticación Segura**: Sistema de login con recuperación de contraseña
- **📱 Interfaz Reactiva**: Componentes Livewire para una experiencia fluida
- **🌐 API REST**: Endpoints para integración con aplicaciones móviles
- **🔗 Login Social**: Integración con redes sociales

## 🏛️ Arquitectura del Sistema

### Patrón Action-Service
El proyecto implementa el patrón **Action-Service** para una arquitectura limpia y mantenible:

```php
// Actions encapsulan la lógica de negocio
class CreateClientAction extends Action
{
    public function execute($data): ActionResult
    {
        $this->validatePermissions(['clients.create']);
        
        $validated = $this->validateData($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string',
            'license_number' => 'required|string|unique:clients,license_number',
        ]);

        return DB::transaction(function () use ($validated) {
            $client = Client::create($validated);
            return $this->successResult($client, 'Cliente creado exitosamente');
        });
    }
}
```

### Services para Lógica de Dominio
```php
// Services manejan operaciones específicas del dominio
class ClientService extends Service
{
    protected string $modelClass = Client::class;

    public function getActiveClients(): Collection
    {
        return Client::where('status', 'active')
            ->with(['vehicles', 'services'])
            ->get();
    }

    public function getClientServices(int $clientId): Collection
    {
        return Service::where('client_id', $clientId)
            ->with(['vehicle'])
            ->orderBy('date', 'desc')
            ->get();
    }
}
```

## 🛠️ Instalación

### Requisitos Previos
- PHP 8.2 o superior
- Composer
- Node.js y npm
- Base de datos (MySQL, PostgreSQL, etc.)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/special-one-rexville-dash.git
cd special-one-rexville-dash
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos**
```env
# Editar .env con tu configuración de base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rexville_dash
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. **Ejecutar migraciones**
```bash
php artisan migrate
php artisan db:seed
```

6. **Compilar assets**
```bash
npm run dev
```

7. **Iniciar servidor**
```bash
php artisan serve
```

## 🎯 Funcionalidades del Sistema

### 👥 Gestión de Clientes
- Registro completo de clientes con información personal
- Número de licencia de conducir
- Información de contacto (teléfono, email)
- Foto de perfil
- Estado activo/inactivo

### 🚗 Gestión de Vehículos
- Registro de vehículos por cliente
- Información técnica: año, modelo, VIN
- Fecha de compra
- Información de seguros
- Historial de servicios

### 🔧 Gestión de Servicios
- Registro de servicios realizados
- Asociación con cliente y vehículo
- Fecha y descripción del servicio
- Historial completo por vehículo

### 🎯 Sistema de Promociones
- Creación de promociones con fechas
- Imágenes promocionales
- URLs de redirección
- Estado activo/inactivo
- Validación automática por fechas

### 👨‍💼 Panel de Administración
- Gestión de administradores
- Niveles de acceso diferentes
- Estado activo/inactivo
- Dashboard con métricas

## 📁 Estructura del Proyecto

```
app/
├── Actions/V1/              # Lógica de negocio
│   ├── Auth/               # Autenticación
│   ├── Admin/              # Gestión de administradores
│   ├── Client/             # Gestión de clientes
│   └── Action.php          # Clase base
├── Services/V1/            # Servicios de dominio
│   ├── AdminService.php    # Servicio de administradores
│   ├── ClientService.php   # Servicio de clientes
│   └── Service.php         # Clase base
├── Models/                 # Modelos Eloquent
│   ├── Admin.php          # Modelo de administradores
│   ├── Client.php         # Modelo de clientes
│   ├── Vehicle.php        # Modelo de vehículos
│   ├── Service.php        # Modelo de servicios
│   ├── Promotion.php      # Modelo de promociones
│   └── SocialAccount.php  # Cuentas sociales
├── Livewire/V1/           # Componentes Livewire
│   ├── Auth/              # Componentes de autenticación
│   └── Panel/             # Componentes del panel
└── Http/Controllers/      # Controladores HTTP
```

## 🔗 Rutas Principales

### Rutas Web
- `/` - Redirección al login
- `/login` - Página de inicio de sesión
- `/forgot-password` - Recuperación de contraseña
- `/panel/home` - Dashboard principal
- `/panel/admins` - Gestión de administradores
- `/panel/admins/create` - Crear administrador
- `/panel/admins/{id}/edit` - Editar administrador

### API Endpoints
- `POST /api/login` - Autenticación
- `GET /api/clients` - Listar clientes
- `POST /api/clients` - Crear cliente
- `GET /api/vehicles` - Listar vehículos
- `POST /api/services` - Registrar servicio

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=ClientTest
php artisan test --filter=VehicleTest
php artisan test --filter=ServiceTest
```

## 🔧 Comandos Artisan Disponibles

```bash
# Generar nuevas Actions
php artisan make:action-enhanced CreateVehicleAction

# Generar nuevos Services
php artisan make:service VehicleService --model=Vehicle

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Desarrollo
composer run dev  # Inicia servidor con queue, logs y vite
```

## 📋 Modelos de Datos

### Cliente (Client)
- name, email, password
- phone_code, phone
- license_number (único)
- profile_photo
- status (activo/inactivo)

### Vehículo (Vehicle)
- client_id (relación con cliente)
- year, model, vin
- buy_date, insurance

### Servicio (Service)
- client_id, vehicle_id
- date, name (descripción)

### Promoción (Promotion)
- title, start_date, end_date
- image_url, redirect_url
- status (activo/inactivo)

## 🛡️ Seguridad

- Autenticación con Laravel Sanctum
- Validación de permisos en Actions
- Protección CSRF en formularios
- Validación de datos en todas las operaciones
- Encriptación de contraseñas

## 📱 Tecnologías Utilizadas

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Livewire 3.6, Livewire Flux
- **Base de Datos**: MySQL/PostgreSQL
- **Autenticación**: Laravel Sanctum
- **Social Login**: Laravel Socialite
- **Testing**: PHPUnit
- **Build Tools**: Vite
- **Arquitectura**: Action-Service Pattern

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una branch para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'feat: agregar nueva funcionalidad'`)
4. Push a la branch (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🙏 Créditos

- Desarrollado con [Laravel](https://laravel.com)
- Interfaz reactiva con [Livewire](https://livewire.laravel.com)
- Patrón Action-Service para arquitectura limpia
- Sistema diseñado para talleres mecánicos y centros de servicio

---

<p align="center">
Hecho con ❤️ para la gestión eficiente de servicios vehiculares
</p>
