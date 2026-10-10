<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class GivingController extends Controller
{
    public function redirect()
    {
        $code = Setting::get('espees_merchant_code', config('services.espees.code', 'REPLACE_WITH_YOUR_ESPEES_MERCHANT_CODE'));

        return redirect()->away('https://espees.org/pay?merchant=' . urlencode($code));
    }
}
