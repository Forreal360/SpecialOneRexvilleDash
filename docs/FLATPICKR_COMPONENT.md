# Componente Flatpickr - Documentación

## Descripción

El componente `flatpickr-date` es un selector de fechas reutilizable basado en Flatpickr que permite personalizar el formato de fechas y se integra perfectamente con Laravel Livewire y el framework Flux.

## Características

- ✅ Formato de fecha personalizable
- ✅ Integración con Livewire
- ✅ Soporte para temas claro/oscuro
- ✅ Localización en español
- ✅ Validación de errores
- ✅ Diferentes tamaños
- ✅ Fechas mínimas y máximas
- ✅ Callbacks personalizables
- ✅ Funciones JavaScript globales

## Instalación

El componente ya incluye Flatpickr via CDN, por lo que no requiere instalación adicional.

## Uso Básico

### Formato por defecto (DD/MM/YYYY)

```blade
<x-forms.flatpickr-date
    name="fecha"
    label="Fecha de inicio"
    wire:model="start_date"
/>
```

### Con formato personalizado

```blade
<x-forms.flatpickr-date
    name="fecha"
    label="Fecha de inicio"
    dateFormat="Y-m-d"
    wire:model="start_date"
/>
```

### Con validación de errores

```blade
<x-forms.flatpickr-date
    name="fecha"
    label="Fecha de inicio"
    wire:model="start_date"
    error="{{ $errors->first('start_date') }}"
/>
```

## Propiedades Disponibles

### Propiedades Básicas

| Propiedad | Tipo | Por defecto | Descripción |
|-----------|------|-------------|-------------|
| `name` | string | null | Nombre del campo |
| `id` | string | auto-generado | ID del input |
| `label` | string | null | Etiqueta del campo |
| `placeholder` | string | 'Selecciona una fecha' | Placeholder del input |
| `dateFormat` | string | 'd/m/Y' | Formato de fecha |
| `altFormat` | string | igual a dateFormat | Formato alternativo |
| `defaultDate` | string | null | Fecha por defecto |
| `minDate` | string | null | Fecha mínima permitida |
| `maxDate` | string | null | Fecha máxima permitida |
| `disabled` | boolean | false | Deshabilitar el input |
| `required` | boolean | false | Campo requerido |
| `class` | string | '' | Clases CSS adicionales |

### Propiedades de Livewire

| Propiedad | Tipo | Descripción |
|-----------|------|-------------|
| `wire:model` | string | Modelo Livewire en tiempo real |
| `wire:model.defer` | string | Modelo Livewire diferido |
| `wire:model.live` | string | Modelo Livewire con debounce |

### Propiedades de Apariencia

| Propiedad | Tipo | Por defecto | Descripción |
|-----------|------|-------------|-------------|
| `size` | string | 'lg' | Tamaño del input (xs, sm, md, lg, xl) |
| `locale` | string | 'es' | Idioma del calendario |
| `theme` | string | 'light' | Tema (light, dark) |
| `position` | string | 'auto' | Posición del calendario |

### Tamaños Disponibles

| Tamaño | Altura | Padding | Uso Recomendado |
|--------|--------|---------|-----------------|
| `xs` | 32px | px-3 py-2 | Formularios compactos |
| `sm` | 40px | px-4 py-2.5 | Formularios pequeños |
| `md` | 48px | px-4 py-3 | Formularios estándar |
| `lg` | 56px | px-5 py-3.5 | **Por defecto** - Formularios principales |
| `xl` | 64px | px-6 py-4 | Formularios destacados |

### Propiedades de Configuración

| Propiedad | Tipo | Por defecto | Descripción |
|-----------|------|-------------|-------------|
| `allowInput` | boolean | true | Permitir entrada manual |
| `altInput` | boolean | false | Usar input alternativo (crea dos inputs) |
| `disableMobile` | boolean | true | Deshabilitar en móviles |
| `weekNumbers` | boolean | false | Mostrar números de semana |
| `firstDayOfWeek` | integer | 1 | Primer día de la semana (1=lunes) |

## Formatos de Fecha Disponibles

### Formatos Comunes

```blade
{{-- Formato español --}}
dateFormat="d/m/Y"     {{-- 15/01/2024 --}}

{{-- Formato ISO --}}
dateFormat="Y-m-d"     {{-- 2024-01-15 --}}

{{-- Formato americano --}}
dateFormat="m/d/Y"     {{-- 01/15/2024 --}}

{{-- Formato con guiones --}}
dateFormat="d-m-Y"     {{-- 15-01-2024 --}}

{{-- Formato con puntos --}}
dateFormat="d.m.Y"     {{-- 15.01.2024 --}}

{{-- Sin ceros --}}
dateFormat="j/n/Y"     {{-- 15/1/2024 --}}

{{-- Formato largo --}}
dateFormat="l, j F Y"  {{-- Monday, 15 January 2024 --}}

{{-- Formato corto --}}
dateFormat="j F Y"     {{-- 15 January 2024 --}}
```

### Caracteres de Formato

| Carácter | Descripción | Ejemplo |
|----------|-------------|---------|
| `d` | Día con cero | 01-31 |
| `j` | Día sin cero | 1-31 |
| `m` | Mes con cero | 01-12 |
| `n` | Mes sin cero | 1-12 |
| `Y` | Año completo | 2024 |
| `y` | Año corto | 24 |
| `F` | Nombre del mes | January |
| `M` | Nombre corto del mes | Jan |
| `l` | Nombre del día | Monday |
| `D` | Nombre corto del día | Mon |

## Ejemplos de Uso

### Ejemplo 1: Fecha básica

```blade
<x-forms.flatpickr-date
    name="birth_date"
    label="Fecha de nacimiento"
    placeholder="Selecciona tu fecha de nacimiento"
    wire:model="birth_date"
    error="{{ $errors->first('birth_date') }}"
/>
```

### Ejemplo 2: Fecha con restricciones

```blade
<x-forms.flatpickr-date
    name="start_date"
    label="Fecha de inicio"
    dateFormat="Y-m-d"
    minDate="today"
    maxDate="2024-12-31"
    wire:model="start_date"
    required
/>
```

### Ejemplo 3: Fecha con tema oscuro

```blade
<x-forms.flatpickr-date
    name="event_date"
    label="Fecha del evento"
    theme="dark"
    dateFormat="d/m/Y"
    wire:model="event_date"
    size="lg"
/>
```

### Ejemplo 4: Fecha con formato personalizado

```blade
<x-forms.flatpickr-date
    name="meeting_date"
    label="Fecha de reunión"
    dateFormat="l, j F Y"
    altFormat="d/m/Y"
    wire:model="meeting_date"
    weekNumbers
    firstDayOfWeek="1"
/>
```

### Ejemplo 5: Con input alternativo (dos inputs)

```blade
<x-forms.flatpickr-date
    name="meeting_date"
    label="Fecha de reunión"
    dateFormat="l, j F Y"
    altFormat="d/m/Y"
    altInput="true"
    wire:model="meeting_date"
/>
```

**Nota**: `altInput: true` crea dos inputs: uno oculto con el formato real y otro visible con el formato de visualización. Úsalo solo cuando necesites mostrar un formato diferente al que se envía al servidor.

## Integración con Livewire

### En el Componente Livewire

```php
class CreatePromotionComponent extends Component
{
    public $start_date;
    public $end_date;
    
    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ];
    
    public function createPromotion()
    {
        $this->validate();
        
        // Convertir formato si es necesario
        if ($this->start_date) {
            $this->start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $this->start_date)->format('Y-m-d');
        }
        
        if ($this->end_date) {
            $this->end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $this->end_date)->format('Y-m-d');
        }
        
        // Lógica de creación...
    }
}
```

### En la Vista Livewire

```blade
<form wire:submit.prevent="createPromotion">
    <x-forms.form-field label="Fecha de inicio" for="start_date">
        <x-forms.flatpickr-date
            name="start_date"
            wire:model="start_date"
            error="{{ $errors->first('start_date') }}"
        />
    </x-forms.form-field>
    
    <x-forms.form-field label="Fecha de fin" for="end_date">
        <x-forms.flatpickr-date
            name="end_date"
            wire:model="end_date"
            error="{{ $errors->first('end_date') }}"
        />
    </x-forms.form-field>
    
    <flux:button type="submit">Crear Promoción</flux:button>
</form>
```

## Funciones JavaScript Globales

El componente proporciona funciones globales para manipular las instancias de Flatpickr:

### Obtener instancia

```javascript
const instance = window.getFlatpickrInstance('mi_input_id');
```

### Limpiar fecha

```javascript
window.clearFlatpickrDate('mi_input_id');
```

### Establecer fecha

```javascript
window.setFlatpickrDate('mi_input_id', '2024-01-15');
```

### Ejemplo de uso en JavaScript

```javascript
// Limpiar fecha cuando se hace clic en un botón
document.getElementById('limpiar_fecha').addEventListener('click', function() {
    window.clearFlatpickrDate('fecha_input');
});

// Establecer fecha actual
document.getElementById('hoy').addEventListener('click', function() {
    const today = new Date().toISOString().split('T')[0];
    window.setFlatpickrDate('fecha_input', today);
});
```

## Callbacks Personalizados

Puedes definir callbacks personalizados usando la variable global `window.flatpickrCallbacks`:

```javascript
window.flatpickrCallbacks = {
    onChange: function(selectedDates, dateStr, instance) {
        console.log('Fecha seleccionada:', dateStr);
        // Tu lógica personalizada aquí
    },
    onOpen: function(selectedDates, dateStr, instance) {
        console.log('Calendario abierto');
    },
    onClose: function(selectedDates, dateStr, instance) {
        console.log('Calendario cerrado');
    }
};
```

## Migración desde Inputs de Fecha Nativos

### Antes (input nativo)

```blade
<flux:input
    type="date"
    wire:model="start_date"
    error="{{ $errors->first('start_date') }}"
/>
```

### Después (Flatpickr)

```blade
<x-forms.flatpickr-date
    name="start_date"
    dateFormat="Y-m-d"
    wire:model="start_date"
    error="{{ $errors->first('start_date') }}"
/>
```

## Consideraciones Importantes

1. **Formato de Fecha**: El componente maneja automáticamente la conversión entre formatos de visualización y formato ISO para la base de datos.

2. **Livewire**: Usa `wire:ignore` para evitar conflictos con la re-renderización de Livewire.

3. **Responsive**: El componente es completamente responsive y funciona en dispositivos móviles.

4. **Accesibilidad**: Incluye etiquetas apropiadas y soporte para navegación por teclado.

5. **Temas**: Soporta temas claro y oscuro que se adaptan automáticamente al tema de la aplicación.

## Solución de Problemas

### El calendario no se abre
- Verifica que Flatpickr esté cargado correctamente
- Asegúrate de que el input tenga un ID único
- Revisa la consola del navegador para errores JavaScript

### Las fechas no se actualizan en Livewire
- Verifica que el `wire:model` esté correctamente configurado
- Asegúrate de que el formato de fecha sea compatible
- Usa `wire:model.defer` si necesitas actualización diferida

### El formato no se aplica
- Verifica que el `dateFormat` esté correctamente especificado
- Asegúrate de que el `altFormat` sea diferente si usas `altInput: true`
- Revisa que la localización esté cargada correctamente 
