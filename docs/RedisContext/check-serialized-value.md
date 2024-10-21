### Check a Serialized Value in Redis

#### Step Definition:

This step checks if a serialized value in Redis matches the expected value. It unserializes the value before comparing.

#### Gherkin Example:

```gherkin
When I see in redis serialized value "testSerializedValue" by key "serializedKey"