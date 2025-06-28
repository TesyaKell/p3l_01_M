<?php

namespace App\Http\Middleware;

class VerifyCsrfToken
{
    protected $except = [
        'livewire/upload-file',
    ];
}
