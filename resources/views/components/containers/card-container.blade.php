<div class="bg-white rounded-lg shadow-lg  border border-gray-200 dark:border-zinc-700 dark:bg-zinc-800">
    <div class="px-6 pt-4 pb-4 border-gray-200 dark:border-zinc-700 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div class="{{ !empty(trim($__env->yieldContent('actions'))) ? 'hidden md:block' : 'block' }} md pb-0">
            @yield('description')
        </div>
        <div class="w-full md:w-auto">
            @yield('actions')
        </div>
    </div>
    <flux:separator variant="subtle" class="mb-6" />
    <div class="{{$class ?? ''}}">
        {{ $slot ?? ''}}
    </div>
</div>
