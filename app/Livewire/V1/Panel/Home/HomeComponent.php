<?php

namespace App\Livewire\V1\Panel\Home;

use Livewire\Component;

class HomeComponent extends Component
{
    public $stats = [
        [
            'title' => 'Total Admins',
            'value' => 10,
            'trendUp' => true,
            'trend' => '10%',
        ],
        [
            'title' => 'Total Admins',
            'value' => 10,
            'trendUp' => true,
            'trend' => '10%',
        ],
        [
            'title' => 'Total Admins',
            'value' => 10,
            'trendUp' => true,
            'trend' => '10%',
        ],
        [
            'title' => 'Total Admins',
            'value' => 10,
            'trendUp' => false,
            'trend' => '10%',
        ],
    ];

    public function render()
    {
        return view('v1.panel.home.home-component');
    }
}
