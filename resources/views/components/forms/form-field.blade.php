<div class="flex flex-col lg:flex-row items-center gap-2 w-full px-6">

    <flux:text class="text-base lg:w-1/2 w-full  mb-1 lg:mb-0" :for="$for ?? null">
        {{ $label ?? '' }}
    </flux:text>

    <div class="lg:w-1/2 w-full">
        {{ $slot }}
        <flux:error name="{{$for}}"/>
    </div>
</div>
<flux:separator variant="subtle" />
