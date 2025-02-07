<?php
/***********
**
*		Indicator for Study Carousel
**
************/

namespace App\View\Components;
 
use Illuminate\View\Component;
 
class QuestionCarouselIndicator extends Component
{
 
    /**
     * The Carousel Indicator Target.
     *
     * @var string
     */
    public $target;
	
	/**
     * The Counter
     *
     * @var int
     */
    public $counter;
	
	/**
     * Active slide
     *
     * @var string
     */
    public $active;
	
 
    /**
     * Create the component instance.
     *
     * @param  int $id
     * @param  string  $text
     * @return void
     */
    public function __construct($target, $counter, $active)
    {
        $this->target = $target;
		$this->counter = $counter;
		$this->active = $active;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.question-carousel-indicator');
    }
}

?>