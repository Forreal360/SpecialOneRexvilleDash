<?php

declare(strict_types=1);

namespace App\Livewire\V1\Panel\Promotion;

use Livewire\Component;
use App\Actions\V1\Promotion\CreatePromotionAction;
use App\Livewire\Concerns\HandlesActionResults;

class CreatePromotionComponent extends Component
{
    use HandlesActionResults;

    public $title;
    public $start_date;
    public $end_date;
    public $image_url;
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
        $result = $this->executeAction($this->createPromotionAction, [
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image_url' => $this->image_url,
            'redirect_url' => $this->redirect_url,
            'status' => $this->status,
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.promotion_created_successfully'));
            return $this->redirect(route('v1.panel.promotions.index'), navigate: true);
        }

        session()->flash('error', __('panel.error_creating_promotion'));
    }
}
