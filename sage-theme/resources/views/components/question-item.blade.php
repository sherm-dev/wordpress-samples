<div class="question-item carousel-item {!! 0 == $index ? 'active' : '' !!}" data-index="{{ $index }}" data-question-id="{{ $id }}">{{-- Removed active 5/23 --}}
	<div class="container-fluid">
		<div class="row d-block question-row">
			<div class="col-xs-12">
				<div class="question-container container-fluid">
					<div class="options-container row d-block">
						<div class="col-xs-12 d-flex options-col">
							<a name="question_top">{{-- Top of question item --}}</a>
							<h2>{!! get_question_text($id) !!}</h2>
							<div class="options align-self-center">
								@each('components.study-option-button', get_question_option_ids($id), 'id')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="row d-block pt-3">
			<div class="col">
				<div class="overall-collapse result-hidden overall-results">
					<a name="overall_top">{{-- Top of overall results --}}</a>
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<h3>{!! get_group_name_by_id($id, "") !!}</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-12 results-container chart-placeholder">
								{{-- @include('components.question-overall-view', array('id'	=>	$id, 'option_id' =>	'', 'group_id' => '', 'sub_group_id' => '')) --}}
							</div>
						</div>
					</div>
					<div class="filters-container container-fluid">
						<div class="row d-block">
							<div class="col d-flex filters-col">
								<a name="filters_top">{{-- Top of Filter Buttons --}}</a>
								<div class="filters align-self-center">
								@foreach(get_groups_by_question_id($id) as $group_id)
									@if(get_group_name_by_id($id, "") != get_group_name_by_id($id, $group_id))
										@include('components.filter-button', array('group_id' => $group_id, 'group_name' => get_group_name_by_id($id, $group_id), 'group_type' => 'group'))
									@endif
								@endforeach
								</div>
							</div>
						</div>
					</div>
					<div class="container-fluid mt-0 mb-4 filter-results">
						<div class="row d-block filter-results-row">
							<div class="col">
								<div class="container-fluid sub-groups-container chart-placeholder">
									<!-- GROUP RESULTS -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>