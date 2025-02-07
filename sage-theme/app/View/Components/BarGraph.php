<?php
/***********
**
*		Study Results Bar Graph
**
************/

namespace App\View\Components;
 
use Illuminate\View\Component;
 
class BarGraph extends Component
{
 
    /**
     * The question ID.
     *
     * @var int
     */
    public $id;
	
	/**
     * The option id.
     *
     * @var int
     */
    public $option_id;
	
	/**
     * The group id.
     *
     * @var int
     */
    public $group_id;
 
	/**
     * The sub group id.
     *
     * @var int
     */
    public $sub_group_id;
    /**
     * Create the component instance.
     *
     * @param  int $id
     * 
     */
    public function __construct($id, $option_id, $group_id, $sub_group_id)
    {
        $this->id = $id;
		$this->option_id = $option_id;
		$this->group_id = $group_id;
		$this->sub_group_id = $sub_group_id;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.bar-graph');
    }
}

?>