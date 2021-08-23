# Secure Information Storage REST API

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Run tests

make tests

### API credentials

* User: john
* Password: maxsecure

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file


Configure your .env:
```sh
ENCODE_KEY=b12d770dd4017b776506f2f9942718
```


### API Resources


| Method | URI | Description |
| ------ | ------ | ------ |
| POST | [/login](#post-login) | Login to use the API |
| GET | [/item](#get-item) | List all items related to your user |
| POST | [/item](#item-post) | Create a new Item |
| PUT | [/item/:item_id](#item-put) | Update a Item |
| DELETE | [/item/:item_id](#item-delete) | Delete a Item |


### POST /login

Example: /login

Request body:

     {
         "username": "john",
         "password": "maxsecure"
     }

Response body (Status Code 200):

     {
        "username":"john",
        "roles":["ROLE_USER"]
     }

### GET /item

Example: /item

Request body:

Response body (Status Code 200):

    [
        {
            "id": 1,
            "data": "first item",
            "created_at": {
                "date": "2021-08-17 12:24:19.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "updated_at": {
                "date": "2021-08-20 23:40:19.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            }
        },
        {
            "id": 2,
            "data": "second item",
            "created_at": {
                "date": "2021-08-17 12:24:19.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "updated_at": {
                "date": "2021-08-20 23:40:19.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            }
        }
    ]

### POST /item

Example: /api/products

#### Input (form-data):

| Name(key) | Type | Description | Example(value) |
| ------ | ------ | ------ | ------ |
| data | string | Item name | first data |

Request body (Status Code 200)

    []

### PUT /item/:item_id

Example: /item/2

Request body:

    {
        "data": "updated data"
    }

Response body (Status Code 200)

    []
    
### DELETE /item/:id

Example: /item/2

Request body:

Response body (Status Code 200)

     []
    
