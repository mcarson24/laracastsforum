<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegistrationConfirmationController extends Controller
{
    public function store()
    {
    	try {
	    	$user = User::where('confirmation_token', request('token'))->firstOrFail();
    		
	    	if ($user->confirmed) return redirect('threads')->with('flash', 'Your email has already been confirmed.');

	    	$user->confirm();
    	} catch (\Exception $e) {
    		return redirect(route('threads.index'))->with(['flash' => 'That token is not valid.']);
    	}
		return redirect('threads')->with(['flash' => 'Your account has been confirmed! You can now create threads.']);
    }
}
