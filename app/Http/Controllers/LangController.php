<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
    /**
     * Switch application language
     */
    public function switch($locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'ar'])) {
            abort(400, 'Invalid locale');
        }

        // Store locale in session
        Session::put('locale', $locale);

        // Redirect back to previous page
        return redirect()->back();
    }
}