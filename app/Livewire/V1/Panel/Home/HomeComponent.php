<?php

namespace App\Livewire\V1\Panel\Home;

use Livewire\Component;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Promotion;
use App\Models\Vehicle;


class HomeComponent extends Component
{
    public $stats = [];


    public function mount(){
        $this->stats = [
            [
                'title' => trans('panel.total_admins'),
                'value' => Admin::count()
            ],
            [
                'title' => trans('panel.total_clients'),
                'value' => Client::count(),
            ],
            [
                'title' => trans('panel.total_promotions'),
                'value' => Promotion::count()
            ],
            [
                'title' => trans('panel.total_vehicles'),
                'value' => Vehicle::count()
            ],
        ];
    }

    public function render()
    {
        return view('v1.panel.home.home-component');
    }
}
