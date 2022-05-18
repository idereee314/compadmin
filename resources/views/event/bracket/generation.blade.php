@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
<div class="container">
  <div class="tournament-bracket tournament-bracket--rounded">
	@if(isset($round))
		@for($i = 0; $i < $round; $i++)
		<div class="tournament-bracket__round">
			<h3 class="tournament-bracket__round-title">Тойрог {{$i + 1}}</h3>
			
			@if($i == 0)
				<ul class="tournament-bracket__list">
				@foreach($members as $member)
					<li class="tournament-bracket__item">
						<div class="tournament-bracket__match" tabindex="0">
							<table class="tournament-bracket__table">
								<tbody class="tournament-bracket__content">
									<tr class="tournament-bracket__team tournament-bracket__team--winner">
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_one}}</abbr>
									</td>
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code">{{ $member->lastname_one != null? $member->lastname_one.' '.$member->firstname_one: 'BYE'}}</abbr><br>
										<span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
									</td>
									
									</tr>
									<tr class="tournament-bracket__team">
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_two}}</abbr>
									</td>
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code">{{ $member->lastname_two != null? $member->lastname_two.' '.$member->firstname_two: 'BYE'}}</abbr><br>								
										<span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
									</td>
									
									</tr>
								</tbody>
							</table>
						</div>
					</li>
				@endforeach
				</ul>
			@else
			<?php
				$total = $total / 2;				
			?>
				<ul class="tournament-bracket__list">
				@for($k = 0; $k < $total; $k++)
					<li class="tournament-bracket__item">
						<div class="tournament-bracket__match" tabindex="0">
							<table class="tournament-bracket__table">
						
							<tbody class="tournament-bracket__content">
								<tr class="tournament-bracket__team">
								<td class="tournament-bracket__country">
									<abbr class="tournament-bracket__code">TBD</abbr>
								</td>
							
								</tr>
								<tr class="tournament-bracket__team tournament-bracket__team--winner">
								<td class="tournament-bracket__country">
									<abbr class="tournament-bracket__code">TBD</abbr>
								</td>
						
								</tr>
							</tbody>
							</table>
						</div>
					</li>
				@endfor
				</ul>
			@endif
    	</div>
		@endfor
	@endif
    
  </div>
  
</div>

@stop