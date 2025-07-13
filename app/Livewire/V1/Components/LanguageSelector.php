<?php

namespace App\Livewire\V1\Components;

use Livewire\Component;

class LanguageSelector extends Component
{
    public $currentLocale;
    public $availableLocales = [
        'es' => 'Español',
        'en' => 'English'
    ];

    public function mount()
    {
        $this->currentLocale = app()->getLocale();
    }

    public function changeLanguage($locale)
    {
        if (array_key_exists($locale, $this->availableLocales)) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
            $this->currentLocale = $locale;

            // Redirigir a la misma página para aplicar el cambio
            return redirect()->to(request()->fullUrlWithQuery(['lang' => $locale]));
        }
    }

    public function render()
    {
        return view('v1.components.language-selector');
    }
}
