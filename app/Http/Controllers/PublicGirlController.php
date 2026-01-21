<?php

namespace App\Http\Controllers;

use App\Models\User;

class PublicGirlController extends Controller
{
    public function show($id)
    {
        $girl = User::findOrFail($id);

        return view('public.girl', compact('girl'));
    }
}
