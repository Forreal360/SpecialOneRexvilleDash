<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Promotion;

use Livewire\Component;
use App\Actions\V1\Promotion\CreatePromotionAction;
use App\Livewire\Concerns\HandlesActionResults;
use Livewire\WithFileUploads;

class CreatePromotionComponent extends Component
{
    use HandlesActionResults, WithFileUploads;

    public $title;
    public $start_date;
    public $end_date;
    public $file;
    public $redirect_url;
    public $status;

    private $createPromotionAction;

    public function boot(CreatePromotionAction $createPromotionAction)
    {
        $this->createPromotionAction = $createPromotionAction;
    }

    public function render()
    {
        return view('v1.panel.promotion.create-promotion-component');
    }

    public function createPromotion()
    {

        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $start_date = $this->start_date . ' ' . '00:00:00';
        $start_date = dateToUTC($start_date, session('timezone'));
        $end_date = $this->end_date . ' ' . '23:59:59';
        $end_date = dateToUTC($end_date, session('timezone'));

        $result = $this->executeAction($this->createPromotionAction, [
            'title' => $this->title,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'file' => $this->file,
            'redirect_url' => $this->redirect_url,
            'status' => $this->status,
        ], true);

        dd($result);
        if ($result->isSuccess()) {
            session()->flash('success', __('panel.promotion_created_successfully'));
            return $this->redirect(route('v1.panel.promotions.index'));
        }

        session()->flash('error', __('panel.error_creating_promotion'));
    }
}
