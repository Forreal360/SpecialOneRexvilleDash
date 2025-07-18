<?php

namespace App\Livewire\V1\Panel\Promotion;

use Livewire\Component;
use App\Models\V1\Promotion;
use App\Actions\V1\Promotion\UpdatePromotionAction;
use App\Services\V1\PromotionService;
use App\Livewire\Concerns\HandlesActionResults;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

class UpdatePromotionComponent extends Component
{
    use HandlesActionResults, WithFileUploads;
    public $promotion_id;
    public $title;
    public $start_date;
    public $end_date;
    public $file;
    public $redirect_url;
    public $status;

    private $updatePromotionAction;
    private $promotionService;

    public function boot(UpdatePromotionAction $updatePromotionAction, PromotionService $promotionService)
    {
        $this->updatePromotionAction = $updatePromotionAction;
        $this->promotionService = $promotionService;
    }

    public function loadPromotion()
    {
        $promotion = $this->promotionService->findByIdOrFail($this->promotion_id);

        $this->title = $promotion->title;
        $this->start_date = \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d H:i:s');
        $this->start_date = dateToLocal($this->start_date, session('timezone'))->format('m/d/Y');

        $this->end_date = \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d H:i:s');
        $this->end_date = dateToLocal($this->end_date, session('timezone'))->format('m/d/Y');
        $this->redirect_url = $promotion->redirect_url;
        $this->status = $promotion->status;
    }

    public function mount($id)
    {
        $this->promotion_id = $id;
        $this->loadPromotion();
    }

    public function render()
    {
        return view('v1.panel.promotion.update-promotion-component');
    }

    public function updatePromotion()
    {
        $this->validate([
            'start_date' => [
                'required',
                Rule::date()->format('m/d/Y'),
            ],
            'end_date' => [
                'required',
                Rule::date()->format('m/d/Y'),
                'after:start_date'
            ],
        ]);

        $start_date = $this->start_date . ' ' . '00:00:00';
        $start_date = dateToUTC($start_date, session('timezone'));
        $end_date = $this->end_date . ' ' . '23:59:59';
        $end_date = dateToUTC($end_date, session('timezone'));

        $result = $this->executeAction($this->updatePromotionAction, [
            'id' => $this->promotion_id,
            'title' => $this->title,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'file' => $this->file,
            'redirect_url' => $this->redirect_url,
            'status' => $this->status,
        ], true);


        if ($result->isSuccess()) {
            session()->flash('success', __('panel.promotion_updated_successfully'));
            return $this->redirect(route('v1.panel.promotions.index'));
        }

        session()->flash('error', __('panel.error_updating_promotion'));
    }
}
