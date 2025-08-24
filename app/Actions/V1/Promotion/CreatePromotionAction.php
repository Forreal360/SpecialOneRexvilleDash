<?php

declare(strict_types=1);

namespace App\Actions\V1\Promotion;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\PromotionService;
use Illuminate\Support\Facades\Storage;

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

        $this->validatePermissions([
            "promotions.create"
        ]);

        // Validate input data
        $validated = $this->validateData($data, [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'redirect_url' => 'required|string|max:255',
        ]);


        $validated['image_path'] = $validated['file'];

        unset($validated['file']);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {

            $this->promotionService->create($validated);

            // Return successful result
            return $this->successResult();
        });
    }
}
