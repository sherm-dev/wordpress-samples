<?php
/***********
**
*		Indicator for Study Carousel
**
************/

namespace App\View\Components;
 
use Illuminate\View\Component;

require_once('/wp-content/themes/sage/resources/lib/StudyDatabaseHelper.php');
 
class QuestionOverallView extends Component
{
	
	/**
     * Question ID
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
        return view('components.question-overall-view');
    }
}

?>