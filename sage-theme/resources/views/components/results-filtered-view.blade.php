<div class="container-fluid mt-3 filter-results">
	<div class="row d-block filter-results-row pt-3 pb-3">
		<div class="col">
			<div class="container-fluid">
				@php
					$group_ids = array();
					$overall_group = get_group_name_by_id($id, "");

				@endphp
				@foreach(get_groups_by_question_id($id) as $group_id)
						@if(!in_array($group_id, $group_ids))
							@if($overall_group != get_group_name_by_id($id, $group_id))
								@php $sub_groups = get_sub_group_ids_by_group($id, $group_id); @endphp
							<div class="row d-block pt-2 pb-2">
								<div class="collapse-group group group-{{ $group_id }} result-hidden" id="group_{!! get_group_name_by_id($id, $group_id) !!}">
									<div class="col d-block mb-2 mt-2">
										<div class="container-fluid">
											<div class="row d-block">
												<div class="col">
													<h3>{{-- get_group_name_by_id($id, $group_id) --}}</h3>
												</div>	
											</div>
											<div class="row d-block">
												<div class="col d-flex filters-col">
													<div class="filters align-self-center">
														@php
															$sub_group_ids = array();
															$count = (2 * 100 / count($sub_groups) * count(get_question_option_ids($id))) . "%";
														@endphp
														@foreach($sub_groups as $sub_group_id)
															@if(!in_array($sub_group_id, $sub_group_ids))
																@if('NONE' != get_sub_group_name_by_id($sub_group_id))
																	@include('components.filter-button', array('group_id' => $sub_group_id, 'group_name' => get_sub_group_name_by_id($sub_group_id), 'group_type' => 'sub_group'))
																@endif
															@endif
															@php
																$sub_group_ids[] = $sub_group_id
															@endphp
														@endforeach
													</div>
												</div>
											</div>
											<div class="row d-block sub-groups">
												<div class="col">
													@php
														$sub_group_ids = array();
														$count = (2 * 100 / count($sub_groups) * count(get_question_option_ids($id))) . "%";
													@endphp
													@foreach($sub_groups as $sub_group_id)
														@if(!in_array($sub_group_id, $sub_group_ids))
																@if('NONE' != get_sub_group_name_by_id($sub_group_id))
																	<div class="container-fluid sub_group sub_group-{{ $sub_group_id }} result-hidden" id="sub_group_{{ $sub_group_id }}">
																		@include('components.question-overall-view', array('id'	=>	$id, 'option_id' =>	'', 'group_id' => $group_id, 'sub_group_id' => $sub_group_id))
																	</div>
																@endif
														@endif
														@php
															$sub_group_ids[] = $sub_group_id
														@endphp
													@endforeach
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endif
						@endif
						@php
							$group_ids[] = $group_id
						@endphp
				@endforeach
			</div>
		</div>
	</div>
</div>