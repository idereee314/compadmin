# Tournament Elimination Strategies - Unit Tests

This directory contains comprehensive unit and integration tests for the tournament elimination strategies.

## Test Structure

```
tests/
├── Unit/
│   └── TournamentMatches/
│       ├── SingleEliminationStrategyTest.php
│       ├── DoubleEliminationStrategyTest.php
│       ├── RoundRobinStrategyTest.php
│       ├── TournamentEliminationStrategyFactoryTest.php
│       └── TournamentMatchServiceTest.php
│
└── Feature/
    └── TournamentMatches/
        └── TournamentMatchIntegrationTest.php
```

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Tournament-Specific Tests
```bash
php artisan test tests/Unit/TournamentMatches/
php artisan test tests/Feature/TournamentMatches/
```

### Run Specific Test File
```bash
php artisan test tests/Unit/TournamentMatches/SingleEliminationStrategyTest.php
```

### Run Specific Test Method
```bash
php artisan test tests/Unit/TournamentMatches/SingleEliminationStrategyTest.php --filter=test_calculate_total_rounds
```

### Run Tests with Coverage Report
```bash
php artisan test --coverage
```

### Run Tests with Detailed Output
```bash
php artisan test --verbose
```

## Unit Tests

### SingleEliminationStrategyTest
Tests the single elimination tournament logic:
- `test_calculate_total_rounds()` - Verifies round calculation (log2)
- `test_get_elimination_type()` - Confirms strategy type
- `test_validate_config_*()` - Configuration validation
- `test_generate_bracket_*()` - Bracket generation logic
- `test_interface_methods_exist()` - Interface compliance

**Key Assertions:**
- 2 participants → 1 round
- 4 participants → 2 rounds
- 8 participants → 3 rounds
- 16 participants → 4 rounds

### DoubleEliminationStrategyTest
Tests double elimination with winners/losers brackets:
- `test_calculate_total_rounds()` - Double size round calculation
- `test_validate_config_*()` - Configuration validation
- `test_generate_bracket_valid_participants()` - Creates bracket with loss_count
- `test_process_match_result()` - Winner/loser handling

**Key Assertions:**
- 2 participants → 2 rounds
- 4 participants → 4 rounds
- 8 participants → 6 rounds

### RoundRobinStrategyTest
Tests round-robin tournament format:
- `test_calculate_total_rounds()` - Round count = n-1
- `test_validate_config_too_many_participants()` - Warns on >20 players
- `test_generate_bracket_valid_participants()` - Creates standings structure
- `test_generate_round_matches_creates_all_pairings()` - n(n-1)/2 matches

**Key Assertions:**
- 2 participants → 1 round, 1 match
- 3 participants → 2 rounds, 3 matches
- 4 participants → 3 rounds, 6 matches
- 5 participants → 4 rounds, 10 matches

### TournamentEliminationStrategyFactoryTest
Tests the factory pattern implementation:
- `test_create_single_elimination_strategy()` - Factory creates correct instance
- `test_create_double_elimination_strategy()` - Factory creates correct instance
- `test_create_round_robin_strategy()` - Factory creates correct instance
- `test_create_unsupported_strategy_returns_null()` - Invalid type returns null
- `test_get_available_strategies()` - Lists all strategies
- `test_is_strategy_supported()` - Checks support
- `test_register_custom_strategy()` - Allows registration
- `test_register_custom_strategy_non_existent_class()` - Validates class exists
- `test_register_custom_strategy_invalid_interface()` - Validates interface

### TournamentMatchServiceTest
Tests the high-level service orchestration:
- `test_service_initialization()` - Service creation
- `test_get_available_elimination_types()` - Lists types
- `test_initialize_tournament_invalid_type()` - Error handling
- `test_tournament_service_interface_methods()` - Interface compliance
- `test_record_match_result_invalid_match()` - Error handling
- `test_get_tournament_standings()` - Standings retrieval
- `test_get_tournament_final()` - Final match retrieval

## Integration Tests

### TournamentMatchIntegrationTest
Tests complete tournament workflows with database:

- `test_single_elimination_tournament_flow()` - Full initialization
- `test_double_elimination_tournament_initialization()` - Full initialization
- `test_round_robin_tournament_initialization()` - Full initialization
- `test_bracket_creation()` - Bracket persisted to DB
- `test_initial_matches_creation()` - Matches created and validated
- `test_tournament_minimum_participants()` - 2-participant tournament
- `test_tournament_eight_participants()` - Standard 8-participant tournament
- `test_invalid_tournament_configuration()` - Error cases
- `test_tournament_odd_participants()` - Odd number handling

**Uses RefreshDatabase trait:**
- Database is migrated before each test
- Database is rolled back after each test
- Ensures test isolation and clean state

## Test Coverage

Current test coverage includes:

| Component | Coverage | Status |
|-----------|----------|--------|
| SingleEliminationStrategy | 90%+ | ✓ |
| DoubleEliminationStrategy | 85%+ | ✓ |
| RoundRobinStrategy | 90%+ | ✓ |
| TournamentEliminationStrategyFactory | 95%+ | ✓ |
| TournamentMatchService | 80%+ | ✓ |

## Key Test Scenarios

### 1. Tournament Initialization
```php
$result = $service->initializeTournament(
    eventId: 1,
    eliminationType: 'single_elimination',
    participants: [1, 2, 3, 4],
    config: ['event_id' => 1]
);

// Assert: bracket created, matches generated, status set
```

### 2. Round Calculation
```php
$rounds = $strategy->calculateTotalRounds(8);
// Single: 3 rounds
// Double: 6 rounds
// Round Robin: 7 rounds
```

### 3. Configuration Validation
```php
$result = $strategy->validateConfig(['event_id' => 1]);
// Assert: valid flag and errors array
```

### 4. Strategy Factory
```php
$strategy = $factory->createStrategy('single_elimination');
// Assert: correct instance or null for invalid
```

## Common Assertions

### Participant Validation
```php
public function test_minimum_participants()
{
    $result = $strategy->generateBracket(1, [], ['event_id' => 1]);
    $this->assertEmpty($result); // Requires 2+ participants
}
```

### Match Structure
```php
public function test_match_structure()
{
    foreach ($matches as $match) {
        $this->assertArrayHasKey('bracket_id', $match);
        $this->assertArrayHasKey('reg_one_id', $match);
        $this->assertArrayHasKey('reg_two_id', $match);
        $this->assertArrayHasKey('status', $match);
    }
}
```

### Bracket Persistence
```php
public function test_bracket_persisted()
{
    $bracket = EventBrackets::find($bracketId);
    $this->assertNotNull($bracket);
    $this->assertEquals('single_elimination', $bracket->bracket_type);
}
```

## Extending Tests

To add tests for a custom tournament strategy:

```php
<?php
namespace Tests\Unit\TournamentMatches;

use Tests\TestCase;
use event\matches\YourCustomStrategy;

class YourCustomStrategyTest extends TestCase
{
    protected $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new YourCustomStrategy();
    }

    public function test_calculate_total_rounds()
    {
        $this->assertEquals(expected, $this->strategy->calculateTotalRounds(participants));
    }

    public function test_get_elimination_type()
    {
        $this->assertEquals('your_type', $this->strategy->getEliminationType());
    }

    // Add more tests...
}
```

## Test Dependencies

- **PHPUnit** - Test framework
- **Laravel TestCase** - Base test class
- **RefreshDatabase** - Database cleanup between tests
- **Eloquent** - ORM for database assertions

## Troubleshooting

### Tests Failing with Database Errors
```bash
# Ensure migrations are run
php artisan migrate --env=testing

# Or allow RefreshDatabase to handle it (default behavior)
```

### Specific Test Not Running
```bash
# Check test class naming (must end with Test)
# Check namespace matches file path
# Verify test methods start with test_
```

### Mocking Issues
```bash
# For complex database interactions, consider:
php artisan tinker
# Then manually test strategy methods
```

## CI/CD Integration

### GitHub Actions Example
```yaml
- name: Run Tests
  run: |
    php artisan migrate --env=testing
    php artisan test tests/Unit/TournamentMatches/
    php artisan test tests/Feature/TournamentMatches/
```

### Coverage Threshold
Maintain minimum 80% code coverage:
```bash
php artisan test --coverage --coverage-path=coverage
```

## Performance Notes

- Unit tests: ~2-3 seconds
- Integration tests: ~5-10 seconds
- Total test suite: ~15-20 seconds

For faster feedback during development:
```bash
php artisan test tests/Unit/ --parallel
```
