<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class DatabaseTest extends TestCase
{
	use DatabaseMigrations;
}