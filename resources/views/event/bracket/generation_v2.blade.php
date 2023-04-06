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
                    $byeList[$key] = array('lastname'=> $member->lastname_one, 'firstname'=> $member->firstname_one, 'academy'=> $member->acname_one);
                }
                else if($member->lastname_one == null && $member->lastname_two != null)
                {
                    $byeList[$key] = array('lastname'=> $member->lastname_two, 'firstname'=> $member->firstname_two, 'academy'=> $member->acname_two);
                }
                else
                {
                    $byeList[$key] = array('lastname'=> null);
                }
            } 
            $total = count($members);
            $winnersBracket = array_chunk($members, $total/2);
            $losersBracket = array();

            // Winners Bracket
            for ($i=0; $i < $round; $i++) { 
                $total = $total / 2;
                ?>
                <div class="tournament-bracket__round">
                    <h3 class="tournament-bracket__round-title">
                        Winners Round {{ $i + 1 }}
                    </h3>
                    <ul class="tournament-bracket__list">
                        <?php
                        $j = 0;
                        foreach ($winnersBracket[$i] as $match) {
                            $j++;
                            ?>
                            <li class="tournament-bracket__item">
                                <div class="tournament-bracket__match" tabindex="0">
                                    <table class="tournament-bracket__table">
                                        <tbody class="tournament-bracket__content">
                                            <tr class="tournament-bracket__team">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ $match->acname_one }}</abbr>
                                                </td>
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">{{ $match->lastname_one != null ? $match->lastname_one . ' ' . $match->firstname_one : 'TBD' }}</abbr><br>
                                                    <span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
                                                </td>
                                            </tr>
                                            <tr class="tournament-bracket__team">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ $match->acname_two }}</abbr>
                                                </td>
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">{{ $match->lastname_two != null ? $match->lastname_two . ' ' . $match->firstname_two : 'TBD' }}</abbr><br>                                
                                                    <span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                            <?php
                            if ($j % 2 == 0) {
                                $losersBracket[] = $match;
                            }
                        }
                        ?>
					</ul>
                </div>
            <?php } ?>
        
        <!-- Losers Bracket -->
        <?php if(count($losersBracket) > 0) { ?>
            <div class="tournament-bracket__round">
                <h3 class="tournament-bracket__round-title">
                    Losers Round 1
                </h3>
                <ul class="tournament-bracket__list">
                    <?php
                    $j = 0;
                    foreach ($losersBracket as $match) {
                        $j++;
                        ?>
                        <li class="tournament-bracket__item">
                            <div class="tournament-bracket__match" tabindex="0">
                                <table class="tournament-bracket__table">
                                    <tbody class="tournament-bracket__content">
                                        <tr class="tournament-bracket__team">
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ $match->acname_one }}</abbr>
                                            </td>
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code">{{ $match->lastname_one != null ? $match->lastname_one . ' ' . $match->firstname_one : 'TBD' }}</abbr><br>
                                                <span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
                                            </td>
                                        </tr>
                                        <tr class="tournament-bracket__team">
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ $match->acname_two }}</abbr>
                                            </td>
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code">{{ $match->lastname_two != null ? $match->lastname_two . ' ' . $match->firstname_two : 'TBD' }}</abbr><br>                                
                                                <span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <?php
                        if ($j % 2 == 0) {
                            $losersBracket[] = $match;
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php } ?>

        <!-- Bye Matches -->
        <?php if(count($byeList) > 0) { ?>
            <div class="tournament-bracket__round">
                <h3 class="tournament-bracket__round-title">
                    Bye Matches
                </h3>
                <ul class="tournament-bracket__list">
                    <?php
                    foreach ($byeList as $member) {
                        if($member['lastname'] != null)
                        {
                            ?>
                            <li class="tournament-bracket__item">
                                <div class="tournament-bracket__match" tabindex="0">
                                    <table class="tournament-bracket__table">
                                        <tbody class="tournament-bracket__content">
                                            <tr class="tournament-bracket__team">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{ $member['academy'] }}</abbr>
                                                </td>
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">{{ $member['lastname'] != null ? $member['lastname'] . ' ' . $member['firstname'] : 'TBD' }}</abbr><br>
                                                    <span class="tournament-bracket__flag flag-icon-{{ $member['country_code'] }}" aria-label="Flag"></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</li>
							<?php
                     	}
                 	}
                 	?>
				</ul>
			</div>
		<?php } ?>
													

