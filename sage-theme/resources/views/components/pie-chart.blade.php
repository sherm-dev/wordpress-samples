<div class="results pie-chart gradient">
	<div class="graph-background">
		<div id="chart{{$id}}{{$option_id}}{{$group_id}}{{$sub_group_id}}" class="pie-chart" data-question-id="{{ $id }}" data-option-id="{{ $option_id }}"></div>
		@php
			pie_chart_render($id, $option_id, $group_id, $sub_group_id);
		@endphp
	</div>
</div>