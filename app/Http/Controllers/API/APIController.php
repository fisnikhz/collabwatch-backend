<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\APIResponse;

class APIController extends Controller
{
    use APIResponse;

    protected int $perPage = 20;

    public function __construct()
    {
        if ($perPage = (int) \request('items_per_page')) {
            $this->perPage = min(100, $perPage);
        }

        if ($locale = request()->header('Accept-Language')) {
            if (in_array($locale, config('app.available_locales'))) {
                app()->setLocale($locale);
            }
        }
    }
}
