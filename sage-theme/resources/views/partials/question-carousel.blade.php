<section class="sojla-carousel" id="carousel_section">
	<div id="sojla_carousel" class="carousel slide" data-ride="carousel" data-interval="false">
	  @php
		$questions = get_question_ids();
		$counter = 0;
	  @endphp
	 <div class="carousel-inner">
		@foreach($questions as $question)
		  @include('components.question-item', ['id' => $question, 'index' => $loop->index])
		@endforeach
	 </div>
		<button class="btn btn-primary btn-next float-right mr-4" href="#sojla_carousel" role="button" data-slide="next">
			Next
		</button>
		<ol class="carousel-indicators">
		  

		  @foreach($questions as $question)
				@include('components.question-carousel-indicator', ['target' => '#sojla_carousel', 'counter' => $counter, 'active' => 0 == $counter ? 'active' : ''])
			@php
				$counter++
			@endphp
		  @endforeach
	  </ol>
	</div>
</section>
