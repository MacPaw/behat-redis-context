# How loading fixture date in Redis

## 1. Example Directory Structure
Here's how you can organize your project with fixture files for Redis:
```
project-root/
│
├── tests/
│   └── Fixtures/
│       └── Redis/
│           ├── Users.yml
│           └── Orders.yml
│
├── behat.yml
└── composer.json
```

## 2. Create Your Fixture Files
Example of `users.yml`:
```yaml
users:
  1: "John Doe"
  2: "Jane Smith"
  3: "Alice Cooper"
```
In this example:

The users hash contains keys for user IDs (1, 2, 3) and their corresponding names.
When this fixture is loaded, these values will be stored in Redis under the hash key users.

Example of `orders.yml`:
```yaml
orders:
  order_123:
    id: 123
    status: "pending"
    total: 250.50
  order_456:
    id: 456
    status: "completed"
    total: 99.99
```
In this example:

The orders hash contains keys representing specific orders with associated data such as id, status, and total.
When this fixture is loaded, Redis will store each order with the given values.

## 3. Using Redis Fixture Context
Once you have created your fixture files, you can reference them in your Behat feature files using the step definition Given I load redis fixtures.

Example Behat Scenario:
```gherkin
Feature: Test Redis Data in Behat

  Scenario: Check if Redis contains predefined users
    Given I load redis fixtures "users"
    When I see in redis any value by key "users"
    Then I should see in redis array by key "users":
      """
      {
        "1": "John Doe",
        "2": "Jane Smith",
        "3": "Alice Cooper"
      }
      """
```
In this scenario:

The fixture users.yml is loaded into Redis.
The test checks that the users key exists in Redis and contains the expected data.

## 4. Load Multiple Fixtures in One Scenario
   You can load multiple fixtures in a single step by providing a comma-separated list of fixture names.

Example Behat Scenario:
```gherkin
Scenario: Load users and orders fixtures
  Given I load redis fixtures "users, orders"
  When I see in redis any value by key "users"
  Then I should see in redis array by key "users":
    """
    {
      "1": "John Doe",
      "2": "Jane Smith"
    }
    """
  When I see in redis any value by key "orders"
  Then I should see in redis array by key "orders":
    """
    {
      "order_123": {
        "id": 123,
        "status": "pending",
        "total": 250.50
      },
      "order_456": {
        "id": 456,
        "status": "completed",
        "total": 99.99
      }
    }
    """
```
In this scenario:

Both users.yml and orders.yml fixtures are loaded into Redis.
The test checks that both users and orders contain the correct data.

4. Handling Missing Fixtures