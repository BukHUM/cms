<?php

namespace App\View\Components\Auth;

use Illuminate\View\Component;

class PasswordField extends Component
{
    public $name;
    public $label;
    public $placeholder;
    public $required;
    public $autocomplete;
    public $showToggle;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name = 'password',
        string $label = 'รหัสผ่าน',
        string $placeholder = 'กรุณาใส่รหัสผ่านของคุณ',
        bool $required = true,
        string $autocomplete = 'current-password',
        bool $showToggle = true
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->autocomplete = $autocomplete;
        $this->showToggle = $showToggle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('auth.components.password-field');
    }
}
