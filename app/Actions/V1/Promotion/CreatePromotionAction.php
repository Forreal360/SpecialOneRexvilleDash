<?php

declare(strict_types=1);

namespace App\Actions\V1\Promotion;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\PromotionService;

class CreatePromotionAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(private PromotionService $promotionService)
    {
        // Inject services here
        // Example: $this->service = $service;
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {

        // Validate input data
        $validated = $this->validateData($data, [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image_url' => 'required|string|max:255',
            'redirect_url' => 'required|string|max:255',
        ]);


        // Business logic with transaction
        return DB::transaction(function () use ($validated) {

            $this->promotionService->create($validated);

            // Return successful result
            return $this->successResult();
        });
    }
}
