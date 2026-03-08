<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\RoundRobinStrategy;

/**
 * Tests that the standalone RoundRobinStrategy uses break-aware scheduling.
 *
 * The greedy algorithm maximises per-player rest breaks — at each slot it
 * picks the unscheduled pair where both players have been idle the longest.
 */
class RoundRobinBreakSchedulingTest extends TestCase
{
    protected $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new RoundRobinStrategy();
    }

    // =========================================================================
    // Match count and pairing completeness
    // =========================================================================

    /**
     * @dataProvider playerCountProvider
     */
    public function test_correct_match_count($count)
    {
        $players = range(1, $count);
        $expected = $count * ($count - 1) / 2;
        $matches = $this->strategy->generateRoundMatches(1, 1, $players);
        $this->assertCount($expected, $matches);
    }

    /**
     * @dataProvider playerCountProvider
     */
    public function test_all_pairs_present($count)
    {
        $players = range(1, $count);
        $matches = $this->strategy->generateRoundMatches(1, 1, $players);
        $pairs = array_map(fn($m) => [$m['reg_one_id'], $m['reg_two_id']], $matches);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $this->assertContains(
                    [$players[$i], $players[$j]],
                    $pairs,
                    "Pair [{$players[$i]},{$players[$j]}] missing"
                );
            }
        }
    }

    /**
     * @dataProvider playerCountProvider
     */
    public function test_order_nos_sequential($count)
    {
        $players = range(1, $count);
        $matches = $this->strategy->generateRoundMatches(1, 1, $players);
        $orderNos = array_column($matches, 'order_no');
        sort($orderNos);
        $expected = range(1, $count * ($count - 1) / 2);
        $this->assertEquals($expected, $orderNos);
    }

    public static function playerCountProvider(): array
    {
        return [[3], [4], [5], [6], [8]];
    }

    // =========================================================================
    // Break scheduling quality
    // =========================================================================

    public function test_4_players_greedy_scheduling()
    {
        $players = [1, 2, 3, 4];
        $matches = $this->strategy->generateRoundMatches(1, 1, $players);

        // P1 should play slots 1, 3, 5 — gap ≥ 2 between each consecutive match
        $p1Slots = $this->getPlayerSlots(1, $matches);
        $this->assertCount(3, $p1Slots);
        $this->assertGreaterThanOrEqual(2, $p1Slots[1] - $p1Slots[0]);
        $this->assertGreaterThanOrEqual(2, $p1Slots[2] - $p1Slots[1]);
    }

    public function test_5_players_each_player_gets_at_least_one_break()
    {
        $players = [1, 2, 3, 4, 5];
        $matches = $this->strategy->generateRoundMatches(1, 1, $players);

        foreach ($players as $p) {
            $slots = $this->getPlayerSlots($p, $matches);
            $hasBreak = false;
            for ($i = 0; $i < count($slots) - 1; $i++) {
                if ($slots[$i + 1] - $slots[$i] >= 2) {
                    $hasBreak = true;
                    break;
                }
            }
            $this->assertTrue($hasBreak, "Player $p should have at least 1 match break somewhere");
        }
    }

    public function test_round2_returns_empty()
    {
        $this->assertEmpty($this->strategy->generateRoundMatches(1, 2, [1, 2, 3, 4]));
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function getPlayerSlots(int $player, array $matches): array
    {
        $slots = [];
        foreach ($matches as $i => $m) {
            if ($m['reg_one_id'] === $player || $m['reg_two_id'] === $player) {
                $slots[] = $i + 1;
            }
        }
        return $slots;
    }
}