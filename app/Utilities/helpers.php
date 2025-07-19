<?php
use Illuminate\Support\Str;
use App\Http\Resources\V1\PaginationResource;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\V1\NotificationResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

/**
 * Generates a random code of uppercase letters and numbers
 * @param int $length
 * @return string
 */
if (! function_exists('getRandomCode')) {
    function getRandomCode(int $length = 5): string {
        $code = strtoupper(Str::random($length));
        return $code;
    }
}

/**
 * Generate pagination resource
 * @param array $resource
 * @param LengthAwarePaginator $pagination
 * @param string $class
 * @param string $key
 * @return PaginationResource
 */
if (! function_exists('generatePaginationResource')) {
    function generatePaginationResource(LengthAwarePaginator $pagination, string $class, string $key = "registers") {

        $registers = $class::collection($pagination->toArray()["data"])->resolve();

        $pagination = PaginationResource::make($registers, $pagination, $key);

        return $pagination;
    }
}

if (! function_exists('dateToUTC')) {
    function dateToUTC($date, $timezone = 'UTC') {
        return Carbon::parse($date, $timezone)->setTimezone('UTC');
    }
}


if (! function_exists('dateToLocal')) {
    function dateToLocal($date, $timezone = 'UTC') {
        return Carbon::parse($date,'UTC')->setTimezone($timezone);
    }
}

