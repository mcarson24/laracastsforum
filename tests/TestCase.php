<?php

namespace Tests;

use App\User;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $oldExceptionHandler;

    public function setUp()
    {
    	parent::setUp();

        \Redis::del('trending_threads');

    	$this->disableExceptionHandling();
    }

    protected function disableExceptionHandling()
    {
    	$this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

    	$this->app->instance(ExceptionHandler::class, new class extends Handler {
    		public function __construct() {}
    		public function report(\Exception $e) {}
    		public function render($request, \Exception $e) {
    			throw $e;
    		}
    	});
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }

    protected function signIn(User $user = null)
    {	
    	$user = $user ?: create(User::class);

    	$this->be($user);

    	return $user;
    }
}
