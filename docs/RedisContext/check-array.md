### Check an Array in Redis

#### Step Definition:

This step checks if the data stored in Redis as a hash matches the expected JSON structure.

#### Gherkin Example:

```gherkin
Then I see in redis array by key "arrayKey":
    """
    {
        "key1": "value1",
        "key2": "value2"
    }
    """