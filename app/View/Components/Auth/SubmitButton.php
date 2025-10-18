<?php

namespace App\View\Components\Auth;

use Illuminate\View\Component;

class SubmitButton extends Component
{
    public $text;
    public $icon;
    public $color;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $text = 'เข้าสู่ระบบ',
        string $icon = 'fas fa-sign-in-alt',
        string $color = 'blue'
    ) {
        $this->text = $text;
        $this->icon = $icon;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('auth.components.submit-button');
    }
}
