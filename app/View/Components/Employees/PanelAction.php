<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class PanelAction extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;
    public $panelActionReasons;

    public function __construct($employees, $stringNumber, $panelActionReasons)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->panelActionReasons = $panelActionReasons;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.panel-action');
    }
}
