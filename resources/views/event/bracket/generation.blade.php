@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
<div class="container">
  <div class="tournament-bracket tournament-bracket--rounded">
	@if(isset($round))

		<?php 
            $byeList = array();

            foreach($members as $key => $member)
            {
                if($member->lastname_one != null && $member->lastname_two == null)
                {
                    $byeList[$key] = array('lastname'=> $member->lastname_one, 'firstname'=> $member->firstname_one, 'academy'=> $member->acname_one, 'is_dq_one'=> $member->is_dq_one);
                }
                else if($member->lastname_one == null && $member->lastname_two != null)
                {
                    $byeList[$key] = array('lastname'=> $member->lastname_two, 'firstname'=> $member->firstname_two, 'academy'=> $member->acname_two, 'is_dq_two'=> $member->is_dq_two);
                }
                else
                {
                    $byeList[$key] = array('lastname'=> null);
                }
            } 
        ?>
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
									@if($member->is_dq_one == true || $member->is_weight_checked_one == false)
										<td class="tournament-bracket__country">
											<del><abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_one}}</abbr></del>
										</td>
										<td class="tournament-bracket__country">
											<del><abbr class="tournament-bracket__code">{{ $member->lastname_one != null? $member->lastname_one.' '.$member->firstname_one: 'BYE'}}</abbr><br></del>
											<span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
											
										</td>
									@else
										<td class="tournament-bracket__country">
											<abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_one}}</abbr>
										</td>
										<td class="tournament-bracket__country">
											<abbr class="tournament-bracket__code">{{ $member->lastname_one != null? $member->lastname_one.' '.$member->firstname_one: 'BYE'}}</abbr><br>
											<span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
											
										</td>
									@endif
									</tr>
									<tr class="tournament-bracket__team">
									@if($member->is_dq_two == true || $member->is_weight_checked_two == false)
										<td class="tournament-bracket__country">
											<del><abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_two}}</abbr></del>
										</td>
										<td class="tournament-bracket__country">
										<del><abbr class="tournament-bracket__code">{{ $member->lastname_two != null? $member->lastname_two.' '.$member->firstname_two: 'BYE'}}</abbr><br></del>
											<span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
										</td>
									@else
										<td class="tournament-bracket__country">
											<abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_two}}</abbr>
										</td>
										<td class="tournament-bracket__country">
											<abbr class="tournament-bracket__code">{{ $member->lastname_two != null? $member->lastname_two.' '.$member->firstname_two: 'BYE'}}</abbr><br>								
											<span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
										</td>
									@endif
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
					@if($i == 1)
						<?php $t = $k*2; ?>					
						<li class="tournament-bracket__item">
							<div class="tournament-bracket__match" tabindex="0">
								<table class="tournament-bracket__table">
							
								<tbody class="tournament-bracket__content">
									<tr class="tournament-bracket__team">
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{@$byeList[$t]['academy']}}</abbr>
									</td>
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code">{!! @$byeList[$t]['lastname'] != null? @$byeList[$t]['lastname'].' <strong>'.@$byeList[$t]['firstname'].'</strong>': 'TBD'!!}</abbr>
									</td>
								
									</tr>
									<tr class="tournament-bracket__team tournament-bracket__team--winner">
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{@$byeList[$t + 1]['academy']}}</abbr>
									</td>
									
									<td class="tournament-bracket__country">
										<abbr class="tournament-bracket__code">{!! @$byeList[$t + 1]['lastname'] != null? @$byeList[$t + 1]['lastname'].' <strong>'.@$byeList[$t + 1]['firstname'].'</strong>': 'TBD'!!}</abbr>
									</td>
							
									</tr>
								</tbody>
								</table>
							</div>
						</li>
					@else
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
						
					@endif
				@endfor
				</ul>
			@endif
    	</div>
		@endfor
	@endif    
  </div>
  <div style="padding-top:50px">	  
  	<a id="print" class="btn btn-primary font-weight-bold" href="/bracket/print/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{trans('display.general_print')}}</a>
  </div>
</div>

@stop