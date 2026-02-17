<?php

namespace App\Features\Fortify;

class CustomFeatures
{
    /**
     * Enable the confirm password feature.
     *
     * @return string
     */
    public static function confirmPasswords()
    {
        return 'confirm-passwords';
    }
}
