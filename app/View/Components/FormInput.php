<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public $type;
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $value;

    public function __construct(
        $name,
        $type = 'text',
        $id = null,
        $label = null,
        $placeholder = null,
        $value = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->id = $id ?? $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.form-input');
    }
}
