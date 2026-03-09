@php
/**
 * Build match lookup arrays from $matchesData (uq_event_matches).
 *
 * Available after @include:
 *   $matchByOrder[order_no]        — winner bracket match by order_no
 *   $loserMatchByOrder[order_no][] — loser bracket matches by order_no
 *   $winnerMatchesList[]           — all winner matches in order
 *   $loserMatchesList[]            — all loser matches in order
 *
 * Each match object has: match_id, order_no, status, is_double_loser,
 *   reg_one_id, firstname_one, lastname_one, acname_one,
 *   reg_two_id, firstname_two, lastname_two, acname_two,
 *   reg_win_id, firstname_win, lastname_win, acname_win,
 *   red_score, blue_score, win_method, etc.
 */
$matchByOrder = [];
$loserMatchByOrder = [];
$winnerMatchesList = [];
$loserMatchesList = [];

if (isset($matchesData) && is_array($matchesData)) {
    foreach ($matchesData as $m) {
        if ($m->is_double_loser) {
            $loserMatchByOrder[$m->order_no][] = $m;
            $loserMatchesList[] = $m;
        } else {
            $matchByOrder[$m->order_no] = $m;
            $winnerMatchesList[] = $m;
        }
    }
}

/**
 * Helper: render player name or empty placeholder.
 */
$fmtPlayer = function($lastname, $firstname, $academy = '') {
    if (!empty($lastname)) {
        return e($lastname) . ' <strong>' . e($firstname) . '</strong>';
    }
    return '';
};

/**
 * Helper: get score display for a match.
 * Returns ['red' => '3', 'blue' => '1'] or ['red' => '', 'blue' => ''] if not completed.
 */
$getScores = function($match) {
    if (!$match || $match->status !== 'C') {
        return ['red' => '', 'blue' => ''];
    }
    $r = '';
    $b = '';
    if ($match->red_score !== null || $match->blue_score !== null) {
        $r = (int)$match->red_score;
        $b = (int)$match->blue_score;
        if ($match->red_advantage || $match->blue_advantage) {
            $r .= '(' . (int)$match->red_advantage . ')';
            $b .= '(' . (int)$match->blue_advantage . ')';
        }
    }
    return ['red' => $r, 'blue' => $b];
};

/**
 * Helper: check if a player is the winner.
 */
$isWinner = function($regId, $match) {
    return $match && $match->status === 'C' && $match->reg_win_id && $match->reg_win_id == $regId;
};
@endphp