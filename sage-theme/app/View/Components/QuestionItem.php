<?php

namespace App\View\Components;
 
use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
 
class QuestionItem extends Component
{	
	/**
	* Question id
	*
	* @var int
	*/
	public $id;
	
	public $index;
	
		
 
    /**
     * Create the component instance.
     *
     * @param  int  $counter
     * @param  obj  $question
	 * 
     * @return void
     */
    public function __construct($id, $index)
    {	
		$this->id = $id; 
		$this->index = index;
    }
	
	public function ajaxRender($id, $index){
		$data = [
			'view' => View::make('components.question-item')
				->with(['id' => $id, 'index' => $index])
				->render()
		];

		return $data;
	}
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.question-item');
    }
}
?>