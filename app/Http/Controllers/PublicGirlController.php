<?php

namespace App\Http\Controllers;

use App\Models\User;

class PublicGirlController extends Controller
{
   public function showFull($id)
{
    $girl = User::findOrFail($id);
    return view('chica.show', compact('girl'));

}
    
}
