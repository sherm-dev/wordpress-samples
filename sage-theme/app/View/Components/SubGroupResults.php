<?php

namespace App\View\Components;
 
use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
 
class SubGroupResults extends Component
{	
	public $id;
	public $group_id;
	public $sub_group_id;
	public $option_id;
		
 
    public function __construct($id, $group_id, $sub_group_id, $option_id = null)
    {	
		$this->id = $id; 
		$this->group_id = $group_id;
		$this->sub_group_id = $sub_group_id;
		$this->option_id = $option_id;
    }
	
	public function ajaxRender($id, $group_id, $sub_group_id, $option_id = null){
		$data = [
			'view' => View::make('components.sub-group-results')
				->with(array('id' => $id, 'group_id' => $group_id, 'sub_group_id' => $sub_group_id))
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
        return view('components.sub-group-results');
    }
}
?>