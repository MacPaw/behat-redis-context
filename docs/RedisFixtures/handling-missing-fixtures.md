# Handling Missing Fixtures
If the specified fixture file is not found, an exception is thrown, indicating that the fixture is missing. Make sure the fixtures exist in the correct directory specified by data_fixtures_path.

Example Error Message:
```gherkin
The "orders" redis fixture not found.
```