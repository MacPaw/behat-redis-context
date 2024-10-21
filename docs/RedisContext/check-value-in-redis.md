### Check a Value in Redis

#### Step Definition:

This step checks if a specific value exists in Redis under the specified key. If the value is missing or does not match, the step will throw an error.

#### Gherkin Example:

```gherkin
When I see in redis value "testValue" by key "testKey"