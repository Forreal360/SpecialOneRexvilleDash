@section('title', __('panel.promotions'))
@section('description', __('panel.promotion_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_promotions') }}</flux:breadcrumbs.item>
@endsection

@section('actions')
@can('promotions.create')
<x-buttons.button-module
    icon="plus"
    href="{{ route('v1.panel.promotions.create') }}"
    label="{{ __('panel.new_promotion') }}"
    variant="primary"
/>
@endcan
@endsection

<x-containers.card-container>
    <x-table.table
        :data="$promotions"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search_promotions') }}"
    >
        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>{{ __('panel.filter_by_date_type') }}</flux:label>
                <flux:select wire:model.live="date_type" size="sm">
                    <flux:select.option value="created_at">{{ __('panel.created_at') }}</flux:select.option>
                    <flux:select.option value="start_date">{{ __('panel.start_date') }}</flux:select.option>
                    <flux:select.option value="end_date">{{ __('panel.end_date') }}</flux:select.option>
                </flux:select>
            </flux:field>

            <x-forms.flatpickr-date
                name="filter_start_date"
                wire:model.live="filter_start_date"
                size="sm"
                label="{{ __('panel.filter_start_date') }}"
                dateFormat="m/d/Y"
                placeholder="{{ __('panel.filter_start_date') }}"
                locale="{{ app()->getLocale() }}"
            />

            <x-forms.flatpickr-date
                name="filter_end_date"
                wire:model.live="filter_end_date"
                size="sm"
                label="{{ __('panel.filter_end_date') }}"
                dateFormat="m/d/Y"
                placeholder="{{ __('panel.filter_end_date') }}"
                locale="{{ app()->getLocale() }}"
            />

            <flux:field class="w-full">
                <flux:label>{{ __('panel.status') }}</flux:label>
                <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.status') }}">
                    <flux:select.option value="A">{{ __('panel.active') }}</flux:select.option>
                    <flux:select.option value="I">{{ __('panel.inactive') }}</flux:select.option>
                </flux:select>
            </flux:field>
        </x-slot>

        <x-slot name="colums">
            <x-table.colum
                sortable="true"
                sortField="title"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >{{ __('panel.name') }}</x-table.colum>
            <x-table.colum
                sortable="false"
            >{{ __('panel.image') }}</x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="start_date"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >{{ __('panel.date') }} {{ __('panel.start') }}</x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="end_date"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >{{ __('panel.date') }} {{ __('panel.end') }}</x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="status"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >{{ __('panel.status') }}</x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="created_at"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >{{ __('panel.created_at') }}</x-table.colum>
            <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
        </x-slot>
        <x-slot name="rows">
            @foreach($promotions as $promotion)
            @php
                $timezone = session('timezone') ?? 'UTC';

                $now = \Carbon\Carbon::now();
                $now = dateToLocal($now, $timezone);

                $start_date = \Carbon\Carbon::parse($promotion->start_date);
                $start_date = dateToLocal($start_date, $timezone);

                $end_date = \Carbon\Carbon::parse($promotion->end_date);
                $end_date = dateToLocal($end_date, $timezone);

                $created_at = \Carbon\Carbon::parse($promotion->created_at);
                $created_at = dateToLocal($created_at, $timezone);
            @endphp
            <x-table.row>
                <x-table.cell>
                    {{$promotion->title}}
                </x-table.cell>
                <x-table.cell>
                    <flux:button icon="photo" icon:variant="outline" class="cursor-pointer" href="{{$promotion->image_path}}" target="_blank"></flux:button>
                </x-table.cell>
                <x-table.cell>
                    {{$start_date->format('m/d/Y H:i')}}
                </x-table.cell>
                <x-table.cell>

                    {{$end_date->format('m/d/Y H:i')}}
                </x-table.cell>
                <x-table.cell>
                    @if($now->lessThan($end_date))
                    <flux:badge color="{{$promotion->status == 'A' ? 'lime' : 'red'}}">
                        {{$promotion->status == 'A' ? __('panel.active') : __('panel.inactive')}}
                    </flux:badge>
                    @else
                    <flux:badge color="red">
                        {{__('panel.expired')}}
                    </flux:badge>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    {{$created_at->format('m/d/Y H:i')}}
                </x-table.cell>
                <x-table.cell>
                    <flux:button.group>
                        <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.promotions.edit', $promotion->id) }}"></flux:button>

                        @if($now->lessThan($end_date))
                            @if($promotion->status == 'A')
                            <flux:tooltip content="{{ __('panel.tooltip_deactivate') }}">
                                <flux:button size="sm" icon="hand-thumb-down" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$promotion->id}}, 'I')" wire:confirm="{{ __('panel.confirm_deactivate') }} {{ __('panel.promotion') }}">
                                </flux:button>
                            </flux:tooltip>
                            @else
                            <flux:tooltip content="{{ __('panel.tooltip_activate') }}">
                                <flux:button size="sm" icon="hand-thumb-up" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$promotion->id}}, 'A')" wire:confirm="{{ __('panel.confirm_activate') }} {{ __('panel.promotion') }}">
                                </flux:button>
                            </flux:tooltip>
                            @endif
                        @endif
                    </flux:button.group>
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot>
    </x-table.table>
</x-containers.card-container>
