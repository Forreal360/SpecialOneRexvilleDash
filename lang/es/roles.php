<?php

return [
    // Títulos y navegación
    'roles' => 'Roles',
    'create_role' => 'Crear Rol',
    'edit_role' => 'Editar Rol',
    'role_management' => 'Gestión de Roles',

    // Campos del formulario
    'role_alias' => 'Nombre del Rol',
    'role_alias_placeholder' => 'Ej: Administrador, Editor, Moderador',
    'alias_description' => 'Ej: Administrador, Editor, Moderador',

    // Permisos
    'select_permissions' => 'Seleccionar Permisos',
    'permissions' => 'permisos',
    'select_all_permissions' => 'Seleccionar Todos',
    'deselect_all_permissions' => 'Deseleccionar Todos',
    'select_all_permissions_global' => 'Seleccionar Todos los Permisos',
    'deselect_all_permissions_global' => 'Deseleccionar Todos los Permisos',
    'no_permissions_available' => 'No hay permisos disponibles',

    // Breadcrumbs
    'breadcrumb_roles' => 'Roles',
    'breadcrumb_create' => 'Crear',
    'breadcrumb_edit' => 'Editar',

    // Mensajes
    'role_created_successfully' => 'Rol creado exitosamente',
    'role_updated_successfully' => 'Rol actualizado exitosamente',
    'role_deleted_successfully' => 'Rol eliminado exitosamente',

    // Botones
    'create' => 'Crear',
    'update' => 'Actualizar',
    'cancel' => 'Cancelar',
    'save' => 'Guardar',
    'delete' => 'Eliminar',
    'edit' => 'Editar',
    'view' => 'Ver',
    'back' => 'Volver',

    // Estados
    'loading' => 'Cargando...',
    'processing' => 'Procesando...',

    // Tabla
    'name' => 'Nombre',
    'alias' => 'Alias',
    'permissions_count' => 'Permisos',
    'created_at' => 'Fecha de Creación',
    'actions' => 'Acciones',
    'no_roles_found' => 'No se encontraron roles',
    'search_roles' => 'Buscar roles...',
    'no_permissions_assigned' => 'Sin permisos asignados',

    // Confirmaciones
    'confirm_delete' => '¿Estás seguro de que deseas eliminar este rol?',
    'confirm_delete_message' => 'Esta acción no se puede deshacer.',
    'cannot_delete_role_in_use' => 'No se puede eliminar el rol porque hay administradores que lo tienen asignado.',

    // Validaciones
    'name_required' => 'El nombre del rol es obligatorio',
    'name_unique' => 'Ya existe un rol con este nombre',
    'permissions_invalid' => 'Uno o más permisos seleccionados no son válidos',

    // Traducciones de alias de permisos
    'permission_aliases' => [
        // Roles
        'get_roles' => 'Ver Roles',
        'create_roles' => 'Crear Roles',
        'update_roles' => 'Actualizar Roles',
        'delete_roles' => 'Eliminar Roles',
        'assign_permissions_to_roles' => 'Asignar Permisos a Roles',

        // Administradores
        'get_administrators' => 'Ver Administradores',
        'create_administrators' => 'Crear Administradores',
        'update_administrators' => 'Actualizar Administradores',
        'update_administrators_status' => 'Actualizar Estado de Administradores',

        // Promociones
        'get_promotions' => 'Ver Promociones',
        'create_promotions' => 'Crear Promociones',
        'update_promotions' => 'Actualizar Promociones',
        'update_promotions_status' => 'Actualizar Estado de Promociones',

        // Servicios de Vehículos
        'get_vehicle_services' => 'Ver Servicios de Vehículos',
        'create_vehicle_services' => 'Crear Servicios de Vehículos',
        'update_vehicle_services' => 'Actualizar Servicios de Vehículos',
        'update_vehicle_services_status' => 'Actualizar Estado de Servicios de Vehículos',

        // Clientes
        'get_clients' => 'Ver Clientes',
        'create_clients' => 'Crear Clientes',
        'update_clients' => 'Actualizar Clientes',
        'update_clients_status' => 'Actualizar Estado de Clientes',

        // Vehículos de Clientes
        'get_clients_vehicles' => 'Ver Vehículos de Clientes',
        'create_clients_vehicles' => 'Crear Vehículos de Clientes',
        'update_clients_vehicles' => 'Actualizar Vehículos de Clientes',
        'update_clients_vehicles_status' => 'Actualizar Estado de Vehículos de Clientes',

        // Servicios de Vehículos de Clientes
        'get_clients_vehicles_services' => 'Ver Servicios de Vehículos de Clientes',
        'create_clients_vehicles_services' => 'Crear Servicios de Vehículos de Clientes',
        'update_clients_vehicles_services' => 'Actualizar Servicios de Vehículos de Clientes',
        'update_clients_vehicles_services_status' => 'Actualizar Estado de Servicios de Vehículos de Clientes',

        // Citas
        'get_appointments' => 'Ver Citas',
        'update_appointments' => 'Actualizar Citas',
        'update_appointments_status' => 'Actualizar Estado de Citas',

        // Tickets
        'get_tickets' => 'Ver Tickets',
        'respond_tickets' => 'Responder Tickets',
        'update_tickets_status' => 'Actualizar Estado de Tickets',
    ],
];
