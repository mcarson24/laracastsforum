<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('Innocent Reply'));

        try {
			$spam->detect('This reply is from Yahoo Customer Support');
        } catch (\Exception $e) {
            return;
        }
        $this->fail('Spam replies should not have been saved successfully.');
    }

    /** @test */
    public function it_checks_for_keys_being_held_down()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('No keys being held down here.'));

        try {
	        $spam->detect('Hellooooooooooooooo wooooooooooooooooooooooooooooooorld!!!!!!!!!!!!!!!!!!!!!!!!!');
        } catch (\Exception $e) {
        	return;
        }
        $this->fail('Spam replies should not have been saved successfully.');
    }
}
