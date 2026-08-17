<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Tampilkan halaman Syarat dan Ketentuan.
     */
    public function terms()
    {
        return view('public.pages.terms');
    }

    /**
     * Tampilkan halaman Hubungi Kami.
     */
    public function contact()
    {
        return view('public.pages.contact');
    }
}
