<div class="results bar-graph gradient">
	<div class="graph-background">
		<div id="graph{{$id}}{{$option_id}}{{$group_id}}{{$sub_group_id}}" data-question-id="{{ $id }}" data-option-id="{{ $option_id }}"></div>
		@php
			bar_graph_render($id, $option_id, $group_id, $sub_group_id);
		@endphp
	</div>
</div>