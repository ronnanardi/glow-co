<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectableOption extends Component
{
    public function __construct(
        public string $name,
        public string $value,
        public bool $checked = false,
        public string $center = '',
    ) {}

    public function render()
    {
        return view('components.selectable-option');
    }
}