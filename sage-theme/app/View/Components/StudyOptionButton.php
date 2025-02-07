<?php
/***********
**
*		Study Option Button for Study Carousel
**
************/

namespace App\View\Components;
 
use Illuminate\View\Component;
 
class StudyOptionButton extends Component
{
 
    /**
     * The option ID.
     *
     * @var int
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
        return view('components.study-option-button');
    }
}

?>