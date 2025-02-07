<?php
/***********
**
*		Indicator for Study Carousel
**
************/

namespace App\View\Components;
 
use Illuminate\View\Component;
 
class ResultsFilteredView extends Component
{
 
    /**
     * The Carousel Indicator Target.
     *
     * @var array
     */
    public $id;
	
	
 
    /**
     * Create the component instance.
     *
     * @param  int $id
     * @param  string  $text
     * @return void
     */
    public function __construct($id)
    {
		$this->id = $id;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.results-filtered-view');
    }
}

?>