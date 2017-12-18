<?php

namespace App\Rules;

use Zttp\Zttp;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'    => config('services.recaptcha.secret'),
            'response'  => $value,
            'remoteip'  => request()->ip()
        ]);

        return $response->json()['success'];

        // if (!$response->json()['success']) throw new \Exception('No Robots Allowed! Failed Recaptcha.');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No robots allowed! Recaptcha failed, please try again.';
    }
}
