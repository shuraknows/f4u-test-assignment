## Deploy

### Clone and install:
~~~~
git clone git@github.com:shuraknows/f4u-test-assignment.git
composer install
~~~~

### Prepare database
~~~~
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
~~~~

### Run server
~~~~
bin/console server:run
~~~~

## API Call examples
Add Shipping Address
~~~~
curl --request POST \
  --url http://127.0.0.1:8001/clients/6fe7ac75-479b-4aa0-a7b3-9b0705918a8b/addresses \
  --header 'content-type: application/json' \
  --data '{
	
	"country": "US",
	"city": "Detroit",
  "zipCode": "123123",
	"street": "Elm Street"
}'
~~~~
 Get Client data
~~~~
curl --request GET \
  --url http://127.0.0.1:8000/clients/6fe7ac75-479b-4aa0-a7b3-9b0705918a8b
~~~~

Get Address data
~~~
curl --request GET \
  --url http://127.0.0.1:8000/clients/6fe7ac75-479b-4aa0-a7b3-9b0705918a8b/addresses/09a52a93-da3c-45a2-8b97-fbe3688686c7
~~~
Delete address
~~~~
curl --request DELETE \
  --url http://127.0.0.1:8000/clients/6fe7ac75-479b-4aa0-a7b3-9b0705918a8b/addresses/cccf6332-9644-43c3-a7c1-39d4df4cebbb
~~~~
Set default ShippingAddress
~~~~
curl --request POST \
  --url http://127.0.0.1:8000/clients/6fe7ac75-479b-4aa0-a7b3-9b0705918a8b/addresses/09a52a93-da3c-45a2-8b97-fbe3688686c7/set-default 
~~~~
Update Shipping Address
~~~~
curl --request POST \
  --url http://127.0.0.1:8000/clients/6fe7ac75-479b-4aa0-a7b3-9b0705918a8b/addresses/09a52a93-da3c-45a2-8b97-fbe3688686c7 \
  --header 'content-type: application/json' \
  --data '{
	"country": "Russia",
	"city": "Moscow",
  "zipCode": "123123",
	"street": "Nikolsaya str"
}'
~~~~

