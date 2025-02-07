<?php

namespace App\View\Components;
 
use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
 
class GroupResults extends Component
{	
	
	public $id;
	public $group_id;
	public $option_id;
		
 
    public function __construct($id, $group_id, $option_id)
    {	
		$this->id = $id; 
		$this->group_id = $group_id;
		$this->option_id = $option_id;
    }
	
	public function ajaxRender($id, $group_id, $option_id){
		$data = [
			'view' => View::make('components.group-results')
				->with(array('id' => $id, 'group_id' => $group_id, 'option_id' => $option_id))
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
        return view('components.group-results');
    }
}
?>