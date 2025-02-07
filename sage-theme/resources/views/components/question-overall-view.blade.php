<div class="row d-block">
	<div class="col-xs-12 overall">
		<div class="container-fluid">
			<div class="row d-block">
				<div class="col-12">
					@if(!empty($sub_group_id))
						<h3>{!! get_sub_group_name_by_id($sub_group_id) !!}</h3>
					@endif
				</div>
			</div>
			<div class="row">
				<!--<div class="col-12 col-lg-6 col-xl-5 offset-xl-1 pie-chart-container">
					@include('components.pie-chart')
				</div>-->
				<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 bar-graph-container">
					@include('components.bar-graph')
				</div>
			</div>
		</div>
	</div>
</div>




