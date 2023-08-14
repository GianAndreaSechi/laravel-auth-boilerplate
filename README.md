# Laravel 
## Authentication and Authorization Boilerplate

This is a simple ready to use boilerplate with JWT Authentication and Authorization so you can develop your application based on this template.

You can simply login and get access-token for your application template (actually not provide specific action authorization).

It uses Laravel Framework and Tymon JWT Auto (https://jwt-auth.readthedocs.io/en/develop/).

This is not production ready at the moment.

---
### Next functions
Authorization action by role

*At the moment there isn't UPDATE or DELETE user, it will be added as soon as possible.*

---
### Routes
* The API prefix is: *api/v1*
* baseurl: localhost:8000

- SWAGGER: <base_url>/api/v1/documentation;

- GET:
  - <base_url>/api/v1/ping - no auth
  - <base_url>/api/v1/users - auth
  - <base_url>/api/v1/users/list - auth
  - <base_url>/api/v1/users/id/{id} - auth
  - <base_url>/api/v1/users/email/{email} - auth

  - <base_url>/login - routes for web login
- POST:
  - <base_url>/api/v1/login - no auth
  - <base_url>/api/v1/register - no auth
  - <base_url>/api/v1/logout - auth
  - <base_url>/api/v1/users/update - auth

*you can import on Postman the pre-setted collection you can find in Postman/API_Auth_Boilerplate.postman_collection.json*

---
## Default User

Create default user with this parameters

* name
* email
* password
* password_confirmation

if you import db\auth_db_laravel.sql you will already find a user into db:

* email: me@boilerplate.it
* user: admin
* password: @dmin!strator

---
## Setup

You need Composer, PHP and mySql installed.

firstable you need to import db\auth_db_laravel.sql into your mysql istance.

after that you have to create an .env file with the database configuration based on env_base structure.

Launch:

`
\> composer global require laravel/installer
`

Database:
you can import db\auth_db_laravel.sql or you can migrate thing there

`
\> php artisan migrate
`

To generate JWT secret

`
\> php artisan jwt:secret
`

To generate/update Swagger
`
\> php artisan l5-swagger:generate
`

Launch server

`
\> cd src
`

`
\> php artisan serve
`