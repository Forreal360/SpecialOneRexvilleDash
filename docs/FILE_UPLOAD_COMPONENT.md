# Componente File Upload

El componente `x-forms.file-upload` es un componente reutilizable para la subida de archivos con Livewire y Alpine.js que incluye:

- Interfaz de arrastrar y soltar personalizada
- Barra de progreso durante la subida
- Validación de tipos de archivo
- Mensajes de estado personalizables
- Soporte para modo oscuro
- Integración con Livewire

## Uso Básico

```blade
<x-forms.form-field label="Imagen" for="image" :error="$errors->first('image')">
    <x-forms.file-upload
        name="image"
        wireModel="image"
        accept="image/*"
        :error="$errors->first('image')"
        required
    />
</x-forms.form-field>
```

**¡Nota importante!** El componente ahora usa traducciones automáticamente, por lo que no necesitas enviar los textos personalizados a menos que quieras cambiarlos.

## Propiedades Disponibles

| Propiedad | Tipo | Por defecto | Descripción |
|-----------|------|-------------|-------------|
| `name` | string | 'file' | Nombre del campo de entrada |
| `label` | string | '' | Etiqueta del campo (no se usa en este componente) |
| `accept` | string | 'image/*' | Tipos de archivo aceptados |
| `placeholder` | string | 'Seleccionar archivo' | Texto del placeholder |
| `changeText` | string | `__('panel.change_file')` | Texto del botón cuando hay archivo seleccionado |
| `selectText` | string | `__('panel.select_file')` | Texto del botón cuando no hay archivo |
| `uploadingText` | string | `__('panel.uploading_file')` | Texto durante la subida |
| `fileReadyText` | string | `__('panel.file_ready')` | Texto cuando el archivo está listo |
| `fileSelectedText` | string | `__('panel.file_selected')` | Texto cuando se selecciona un archivo |
| `clickToSelectText` | string | `__('panel.click_to_select_file')` | Texto instructivo |
| `error` | string | null | Mensaje de error a mostrar |
| `required` | boolean | false | Si el campo es requerido |
| `wireModel` | string | null | Modelo de Livewire para el campo |

## Ejemplos de Uso

### Subida de Imágenes
```blade
<x-forms.file-upload
    name="profile_image"
    wireModel="profileImage"
    accept="image/*"
    :error="$errors->first('profile_image')"
    required
/>
```

### Subida de Documentos
```blade
<x-forms.file-upload
    name="document"
    wireModel="document"
    accept=".pdf,.doc,.docx"
    :error="$errors->first('document')"
    changeText="Cambiar documento"
    selectText="Seleccionar documento"
    uploadingText="Subiendo documento..."
    fileReadyText="Documento listo"
    fileSelectedText="Documento seleccionado"
    clickToSelectText="Haz clic para seleccionar un documento"
/>
```

### Con Textos Personalizados (Opcional)
```blade
<x-forms.file-upload
    name="file"
    wireModel="file"
    accept="image/*"
    :error="$errors->first('file')"
    changeText="Cambiar documento"
    selectText="Seleccionar documento"
    uploadingText="Subiendo documento..."
    fileReadyText="Documento listo"
    fileSelectedText="Documento seleccionado"
    clickToSelectText="Haz clic para seleccionar un documento"
/>
```

**Nota:** Solo necesitas enviar estos parámetros si quieres personalizar los textos. Por defecto, el componente usa las traducciones del sistema.

## Integración con Livewire

El componente está diseñado para trabajar con Livewire y incluye:

- Eventos de subida (`livewire-upload-start`, `livewire-upload-finish`, etc.)
- Barra de progreso automática
- Manejo de errores de subida
- Actualización en tiempo real del estado

### En el Componente Livewire

```php
class CreatePromotionComponent extends Component
{
    public $file;
    
    public function createPromotion()
    {
        $this->validate([
            'file' => 'required|image|max:2048', // 2MB máximo
        ]);
        
        // Procesar el archivo...
    }
}
```

## Características

- **Responsive**: Se adapta a diferentes tamaños de pantalla
- **Accesible**: Incluye atributos ARIA y navegación por teclado
- **Personalizable**: Todos los textos y estilos son configurables
- **Modo Oscuro**: Soporte completo para temas oscuros
- **Validación**: Integración con el sistema de validación de Laravel
- **Progreso**: Barra de progreso visual durante la subida
- **Estados**: Múltiples estados visuales (vacío, seleccionado, subiendo, listo, error)

## Dependencias

- Alpine.js (para la interactividad)
- Livewire (para la subida de archivos)
- Tailwind CSS (para los estilos) 
