# How Redis Fixtures Work
When the Given I load redis fixtures step is called:

* The system looks for the specified YAML files in the directory defined by data_fixtures_path. 
* Redis is populated with the key-value pairs or hash structures defined in these files. 
* Simple key-value pairs are stored using the SET command, and hash sets are stored using the HMSET command.