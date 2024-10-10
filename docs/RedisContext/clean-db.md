## Redis Database Cleanup

### Before Each Scenario

The Redis database is automatically flushed before each scenario using the `@BeforeScenario` hook. This ensures that each scenario starts with a clean database, preventing test pollution from previous scenarios.

#### Example Usage:

There is no specific Gherkin step required; this happens automatically before each test scenario.

### After Each Feature

After all scenarios in a feature are completed, the system performs garbage collection to free up memory.

#### Example Usage:

There is no specific Gherkin step required; this happens automatically after the last test in a feature.