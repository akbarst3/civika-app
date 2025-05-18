<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    public string $navtitle;

    public function __construct($navtitle = 'Dashboard / Home')
    {
        $this->navtitle = $navtitle;
    }

    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
