# Componentes Livewire que NO siguen la Arquitectura Action-Service

Este documento lista todos los componentes Livewire que no siguen correctamente el patrón de arquitectura Action-Service definido en el proyecto.

## 🔴 Violaciones Mayores (Requieren Refactoring Completo)

### 1. GetPromotionsComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Promotion/GetPromotionsComponent.php`

**Violaciones:**
- **Líneas 86-103:** Consultas directas a la base de datos en el método render()
- **Línea 98:** Lógica de negocio para conversión de zona horaria `dateToUTC($start_date, session('timezone'))`
- **Líneas 115-118:** Actualización directa del modelo `Promotion::find($id)?->update(['status' => $status]);`

**Debe moverse a Actions:**
- Crear `GetPromotionsAction` para la lógica de consulta
- Crear `UpdatePromotionStatusAction` para actualizaciones de estado

---

### 2. ResetPasswordComponent.php
**Ubicación:** `app/Livewire/V1/Auth/ResetPasswordComponent.php`

**Violaciones:**
- **Líneas 41-57:** Lógica compleja de reset de contraseña con Laravel Password broker
- **Líneas 48-56:** Manipulación directa de modelos y disparar eventos

**Debe moverse a Actions:**
- Crear `ResetPasswordAction` para toda la lógica de reset

---

### 3. GetAdminsComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Admin/GetAdminsComponent.php`

**Violaciones:**
- **Líneas 66-77:** Consultas directas con lógica de búsqueda compleja
- **Líneas 89-92:** Actualización directa del modelo `Admin::find($id)->update(['status' => $status]);`

**Debe moverse a Actions:**
- Usar `AdminService` para consultas
- Crear `UpdateAdminStatusAction` para actualizaciones

---

### 4. ForgotPasswordComponent.php
**Ubicación:** `app/Livewire/V1/Auth/ForgotPasswordComponent.php`

**Violaciones:**
- **Línea 24:** Interacción directa con Password broker `Password::broker('admins')->sendResetLink(['email' => $this->email]);`

**Debe moverse a Actions:**
- Crear `SendPasswordResetLinkAction`

---

### 5. GetClientsComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Client/GetClientsComponent.php`

**Violaciones:**
- **Líneas 98-127:** Construcción compleja de consultas con múltiples condiciones
- **Líneas 139-142:** Actualización directa del modelo `Client::find($id)->update(['status' => $status]);`

**Debe moverse a Actions:**
- Usar `ClientService` para consultas
- Crear `UpdateClientStatusAction`

---

### 6. HomeComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Home/HomeComponent.php`

**Violaciones:**
- **Líneas 18-35:** Consultas directas de conteo de modelos para estadísticas
- **Líneas 21, 25, 29, 33:** Llamadas directas a modelos `Admin::count()`, `Client::count()`, etc.

**Debe moverse a Actions:**
- Crear `GetDashboardStatsAction` para todas las estadísticas

---

### 7. GetVehicleServicesComponent.php
**Ubicación:** `app/Livewire/V1/Panel/VehicleService/GetVehicleServicesComponent.php`

**Violaciones:**
- **Líneas 70-78:** Consultas directas en el método render()
- **Líneas 91-95:** Actualización directa con mensaje de estado

**Debe moverse a Actions:**
- Usar `VehicleServiceService` para consultas
- Crear `UpdateVehicleServiceStatusAction`

---

### 8. GetClientServicesComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Client/Service/GetClientServicesComponent.php`

**Violaciones:**
- **Líneas 109-134:** Consultas complejas con relaciones y conversiones de fecha
- **Líneas 122-127:** Lógica de negocio para conversión de zona horaria

**Debe moverse a Actions:**
- Usar `ClientServiceService`
- Centralizar lógica de conversión de fechas

---

### 9. GetVehiclesComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Client/Vehicle/GetVehiclesComponent.php`

**Violaciones:**
- **Líneas 132-165:** Consultas complejas con múltiples filtros
- **Líneas 154-156:** Lógica de negocio de parsing de fechas `Carbon::parse($this->buy_date)->format('Y-m-d')`
- **Líneas 181-184:** Actualización directa del modelo

**Debe moverse a Actions:**
- Usar `VehicleService` para consultas
- Crear `UpdateVehicleStatusAction`

---

### 10. CreateRoleComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Role/CreateRoleComponent.php`

**Violaciones:**
- **Líneas 48-67:** Creación directa de modelo y asignación de permisos
- **Líneas 55-63:** Lógica de creación de rol con sincronización de permisos

**Debe moverse a Actions:**
- Crear `CreateRoleAction`

---

### 11. EditRoleComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Role/EditRoleComponent.php`

**Violaciones:**
- **Líneas 63-78:** Actualizaciones directas de modelo y sincronización de permisos
- **Líneas 70-75:** Lógica de actualización de rol

**Debe moverse a Actions:**
- Crear `UpdateRoleAction`

---

### 12. GetRolesComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Role/GetRolesComponent.php`

**Violaciones:**
- **Líneas 51-57:** Lógica de negocio para verificar si el rol puede ser eliminado
- **Líneas 59-76:** Lógica de eliminación de rol con validación
- **Líneas 80-89:** Consultas directas a la base de datos

**Debe moverse a Actions:**
- Crear `DeleteRoleAction`
- Usar `RoleService` para consultas

---

## 🟡 Violaciones Menores (Ajustes Necesarios)

### 13. UpdateClientServiceComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Client/Service/UpdateClientServiceComponent.php`

**Violaciones:**
- **Líneas 39-46:** Carga directa de modelo `ClientService::findOrFail($this->clientServiceId);`
- **Líneas 50-58:** Consultas directas en método render()

**Recomendación:** Usar métodos de servicio para carga de datos

---

### 14. CreateClientServiceComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Client/Service/CreateClientServiceComponent.php`

**Violaciones:**
- **Líneas 36-44:** Consultas directas en método render()

**Recomendación:** Usar servicio para obtener datos

---

### 15. UpdateVehicleServiceComponent.php
**Ubicación:** `app/Livewire/V1/Panel/VehicleService/UpdateVehicleServiceComponent.php`

**Violaciones:**
- **Líneas 33-39:** Carga directa de modelo `VehicleService::findOrFail($this->vehicleServiceId);`

**Recomendación:** Usar método de servicio

---

### 16. GetTicketsComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Ticket/GetTicketsComponent.php`

**Violaciones:**
- **Líneas 86-103:** Consultas complejas con relaciones en render()
- **Líneas 75-82:** Patrón de instanciación de Action incorrecto `app(CloseTicketAction::class)->handle(`

**Recomendación:** Inyectar Action en boot() y usar executeAction()

---

### 17. ViewTicketComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Ticket/ViewTicketComponent.php`

**Violaciones:**
- **Líneas 30-34:** Carga directa de modelo en loadTicket()
- **Líneas 40, 42-46:** Patrón de instanciación de Action incorrecto

**Recomendación:** Usar patrón correcto de inyección de dependencias

---

### 18. GetAppointmentsComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Appointment/GetAppointmentsComponent.php`

**Violaciones:**
- **Líneas 148-176:** Consultas complejas con lógica de zona horaria en render()
- **Líneas 165-170:** Lógica de negocio para conversión de fechas

**Recomendación:** Mover lógica a Service/Action

---

### 19. ConfirmAppointmentComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Appointment/ConfirmAppointmentComponent.php`

**Violaciones:**
- **Líneas 59-84:** Lógica compleja de transacción con actualizaciones directas de tablas
- **Líneas 65-68:** Manipulación directa de tablas de base de datos

**Recomendación:** Crear `ConfirmAppointmentAction`

---

### 20. CompleteAppointmentComponent.php
**Ubicación:** `app/Livewire/V1/Panel/Appointment/CompleteAppointmentComponent.php`

**Violaciones:**
- **Líneas 68-94:** Lógica compleja de transacción similar a ConfirmAppointmentComponent
- **Líneas 74-77:** Manipulación directa de tablas de base de datos

**Recomendación:** Crear `CompleteAppointmentAction`

---

## ✅ Componentes que SÍ siguen el patrón correcto

Los siguientes componentes implementan correctamente la arquitectura Action-Service:

1. ✅ `CreatePromotionComponent.php`
2. ✅ `LoginComponent.php`
3. ✅ `CreateVehicleServiceComponent.php`
4. ✅ `CreateVehicleComponent.php`
5. ✅ `UpdateClientComponent.php`
6. ✅ `CreateClientComponent.php` (Referencia)
7. ✅ `UpdatePromotionComponent.php`
8. ✅ `UpdateAppointmentComponent.php`
9. ✅ `UpdateVehicleComponent.php`
10. ✅ `GetNotificationsComponent.php`
11. ✅ `CreateAdminComponent.php`
12. ✅ `UpdateAdminComponent.php`

Estos componentes correctamente:
- Inyectan Actions via dependency injection en boot()
- Usan el trait HandlesActionResults
- Llaman a executeAction() para lógica de negocio
- No contienen interacciones directas con modelos o reglas de negocio complejas

---

## 📊 Resumen

**Total de componentes analizados:** 32

**Violaciones mayores:** 12 componentes (requieren refactoring completo)
**Violaciones menores:** 8 componentes (ajustes necesarios)
**Implementación correcta:** 12 componentes

### Violaciones más comunes encontradas:

1. 🔴 **Consultas directas a la base de datos en métodos render()**
2. 🔴 **Actualizaciones directas de modelos sin usar Actions**
3. 🔴 **Lógica de negocio compleja en componentes en lugar de Actions**
4. 🔴 **Falta de inyección de dependencias para Actions**
5. 🟡 **Patrón incorrecto de instanciación de Actions (usar app() en lugar de DI)**
6. 🟡 **Carga directa de modelos sin usar Services**

### Próximos pasos recomendados:

1. Comenzar por los componentes con **violaciones mayores**
2. Crear los Actions faltantes siguiendo el patrón establecido
3. Refactorizar componentes para usar executeAction()
4. Mover toda la lógica de negocio a Actions/Services
5. Implementar inyección de dependencias correcta en boot()