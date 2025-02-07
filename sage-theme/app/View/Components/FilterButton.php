<?php
/***********
**
*		Filter Button for group results
**
************/

namespace App\View\Components;
 
use Illuminate\View\Component;
 
class FilterButton extends Component
{
 
    /**
     * Group ID
     *
     * @var int
     */
    public $group_id;
	
	/**
     * Group Name
     *
     * @var string
     */
    public $group_name;
	
	/**
     * Group Type (sub_group or group)
     *
     * @var string
     */
	public $group_type;
	
 
    /**
     * Create the component instance.
     *
     * @param  int $id
     * @param  string  $text
     * @return void
     */
    public function __construct($group_id, $group_name, $group_type)
    {
        $this->group_id = $group_id;
		$this->group_name = $group_name;
		$this->group_type = $group_type;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.filter-button');
    }
}

?>