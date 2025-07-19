<?php

declare(strict_types=1);

namespace App\Actions\V1\Promotion;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\PromotionService;

class UpdatePromotionAction extends Action
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
        $validated = $this->validateData($data, [
            'title' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'redirect_url' => 'required|string|max:255',
        ]);

        if ($validated['file']) {
            $validated['image_path'] = $validated['file'];
            unset($validated['file']);
        }

        // Business logic with transaction
        return DB::transaction(function () use ($validated, $data) {

            $this->promotionService->update((int) $data['id'], $validated);

            // Return successful result
            return $this->successResult(
                data: null, // Replace with your actual result data
                message: 'Operación completada exitosamente'
            );
        });
    }
}
