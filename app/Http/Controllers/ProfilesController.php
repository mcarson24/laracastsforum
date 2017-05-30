<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
    	$profileUser = $user;

    	$threads = $profileUser->threads()->paginate(20);

    	return view('profiles.show', compact('profileUser', 'threads'));
    }
}
