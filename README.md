# Pubs API
Following API returns informations about pubs and bars in a range of 2km for given location.

### Installation
Pubs API requires [vagrant](https://www.vagrantup.com/)

To setup development enviroment simply run:

```sh
$ vagrant up
```

### API documentation
Documentation file is located in:
```sh
web/swagger.json
```
Import it manually to http://editor.swagger.io/#/ (or by url http://editor.swagger.io/#/?import=http://[your_domain]/swagger.json)

### Demo
API is provided with a responsive demo application. Use your favourite browser and visit http://127.0.0.1:8880 

### About the project
Project is setup on Symfony framework [http://symfony.com] v3.1

It uses:
- php 5.5
- mysql 5.5
- Apache 2.4

When the request goes to the API for the first time it is forwarded to the Google Places API. The answer is stored in a local database. Every next request is handled with localy stored data, without using Google API's quota.