<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        public function handle(Request $request, Closure $next): Response
    {
        // Obtener el idioma del parámetro de URL, sesión o configuración por defecto
        $locale = $request->get('lang') ?? $request->session()->get('locale') ?? config('app.locale');

        // Validar que el idioma esté soportado
        $supportedLocales = ['es', 'en'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale');
        }

        // Establecer el idioma
        app()->setLocale($locale);

        // Guardar en sesión para futuras requests
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
