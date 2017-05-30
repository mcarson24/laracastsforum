<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
    	$profileUser = $user;

    	return view('profiles.show', compact('profileUser'));
    }
}
