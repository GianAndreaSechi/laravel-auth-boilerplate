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
* The API prefix is: *api/*
* baseurl: localhost:8000

swagger is still not integrated (i will asap);

- GET:
  - <base_url>/api/ping - no auth
  - <base_url>/api/users - auth
  - <base_url>/api/users/list - auth
   - <base_url>/api/users/id/{id} - auth
  - <base_url>/api/users/email/{email} - auth
- POST:
  - <base_url>/api/users/login - no auth
  - <base_url>/api/users/register - no auth

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

Launch server

`
\> cd src
`

`
\> php artisan serve
`