<?php

namespace App\View\Components\Auth;

use Illuminate\View\Component;

class EmailField extends Component
{
    public $name;
    public $label;
    public $placeholder;
    public $required;
    public $autocomplete;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name = 'email',
        string $label = 'อีเมล์',
        string $placeholder = 'กรุณาใส่อีเมล์ของคุณ',
        bool $required = true,
        string $autocomplete = 'email'
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->autocomplete = $autocomplete;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('auth.components.email-field');
    }
}
