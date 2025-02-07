<div class="row d-block pt-2 pb-2">
	<div class="collapse-group group group-{{ $group_id }}" id="group_{{ $group_id }}" data-group-id="{{ $group_id }}" data-question-id="{{ $id }}">
		<div class="col d-block mb-2 mt-2">
			<div class="container-fluid">
				<div class="row d-block">
					<div class="col d-flex filters-col">
						<a name="group_top">{{-- Top of groups --}}</a>
						<div class="filters align-self-center">
							@php
								$sub_group_ids = array();
								$sub_groups = get_sub_group_ids_by_group($id, $group_id); 
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
					<a name="subgroup_top">{{-- Sub Group Top --}}</a>
					<div class="col chart-placeholder">
						<!--SUB GROUP RESULTS-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>