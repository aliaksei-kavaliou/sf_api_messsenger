Test Task
===

Installation
---
    # clone the repository
    $ git clone https://github.com/aliaksei-kavaliou/sf_api_messsenger.git
    $ cd sf_api_messenger
    
Run
---
    $ docker-compose build && docker-compose up -d
    $ docker-compose exec php composer install
    $ docker-compose exec php bin/console app:aws-init
    
Test
---
    $ docker-compose exec php bin/phpunit
    
Api
---
* POST - /api/v1/transactions
* GET - /api/v1/transactions/:id
* POST - /api/v1/transactions/confirm/:id
