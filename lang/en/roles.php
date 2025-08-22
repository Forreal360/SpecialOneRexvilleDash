<?php

return [
    // Titles and navigation
    'roles' => 'Roles',
    'create_role' => 'Create Role',
    'edit_role' => 'Edit Role',
    'role_management' => 'Role Management',
    
    // Form fields
    'role_alias' => 'Role Name',
    'role_alias_placeholder' => 'Ex: Administrator, Editor, Moderator',
    'alias_description' => 'The technical name will be generated automatically based on this name.',
    
    // Permissions
    'select_permissions' => 'Select Permissions',
    'permissions' => 'permissions',
    'select_all_permissions' => 'Select All',
    'deselect_all_permissions' => 'Deselect All',
    'select_all_permissions_global' => 'Select All Permissions',
    'deselect_all_permissions_global' => 'Deselect All Permissions',
    'no_permissions_available' => 'No permissions available',
    
    // Breadcrumbs
    'breadcrumb_roles' => 'Roles',
    'breadcrumb_create' => 'Create',
    'breadcrumb_edit' => 'Edit',
    
    // Messages
    'role_created_successfully' => 'Role created successfully',
    'role_updated_successfully' => 'Role updated successfully',
    'role_deleted_successfully' => 'Role deleted successfully',
    
    // Buttons
    'create' => 'Create',
    'update' => 'Update',
    'cancel' => 'Cancel',
    'save' => 'Save',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'view' => 'View',
    'back' => 'Back',
    
    // States
    'loading' => 'Loading...',
    'processing' => 'Processing...',
    
    // Table
    'name' => 'Name',
    'alias' => 'Alias',
    'permissions_count' => 'Permissions',
    'created_at' => 'Created At',
    'actions' => 'Actions',
    'no_roles_found' => 'No roles found',
    'search_roles' => 'Search roles...',
    'no_permissions_assigned' => 'No permissions assigned',
    
    // Confirmations
    'confirm_delete' => 'Are you sure you want to delete this role?',
    'confirm_delete_message' => 'This action cannot be undone.',
    'cannot_delete_role_in_use' => 'Cannot delete role because there are administrators that have it assigned.',
    
    // Validations
    'name_required' => 'Role name is required',
    'name_unique' => 'A role with this name already exists',
    'permissions_invalid' => 'One or more selected permissions are invalid',
    
    // Permission aliases translations
    'permission_aliases' => [
        // Roles
        'get_roles' => 'View Roles',
        'create_roles' => 'Create Roles',
        'update_roles' => 'Update Roles',
        'delete_roles' => 'Delete Roles',
        'assign_permissions_to_roles' => 'Assign Permissions to Roles',
        
        // Administrators
        'get_administrators' => 'View Administrators',
        'create_administrators' => 'Create Administrators',
        'update_administrators' => 'Update Administrators',
        'update_administrators_status' => 'Update Administrators Status',
        
        // Promotions
        'get_promotions' => 'View Promotions',
        'create_promotions' => 'Create Promotions',
        'update_promotions' => 'Update Promotions',
        'update_promotions_status' => 'Update Promotions Status',
        
        // Vehicle Services
        'get_vehicle_services' => 'View Vehicle Services',
        'create_vehicle_services' => 'Create Vehicle Services',
        'update_vehicle_services' => 'Update Vehicle Services',
        'update_vehicle_services_status' => 'Update Vehicle Services Status',
        
        // Clients
        'get_clients' => 'View Clients',
        'create_clients' => 'Create Clients',
        'update_clients' => 'Update Clients',
        'update_clients_status' => 'Update Clients Status',
        
        // Clients Vehicles
        'get_clients_vehicles' => 'View Clients Vehicles',
        'create_clients_vehicles' => 'Create Clients Vehicles',
        'update_clients_vehicles' => 'Update Clients Vehicles',
        'update_clients_vehicles_status' => 'Update Clients Vehicles Status',
        
        // Clients Vehicles Services
        'get_clients_vehicles_services' => 'View Clients Vehicles Services',
        'create_clients_vehicles_services' => 'Create Clients Vehicles Services',
        'update_clients_vehicles_services' => 'Update Clients Vehicles Services',
        'update_clients_vehicles_services_status' => 'Update Clients Vehicles Services Status',
        
        // Appointments
        'get_appointments' => 'View Appointments',
        'update_appointments' => 'Update Appointments',
        'update_appointments_status' => 'Update Appointments Status',
        
        // Tickets
        'get_tickets' => 'View Tickets',
        'respond_tickets' => 'Respond Tickets',
        'update_tickets_status' => 'Update Tickets Status',
    ],
];