# Docker PHP Node.js Communication Over a UNIX domain Socket

### Flow

  - The request is initially handled by PHP
  - PHP sends an HTTP request over a UNIX domain socket over to Node.js
  - Node.js sends a responce to PHP
  - PHP renders the responds to the client


### Install

Prerequisites: `npm`, `composer`, `docker-compose`
Install:
  - In the `node` folder, run `npm install`
  - In the `php` folder, run `composer install`


### Run

`docker-compose up -d`

`curl -i http://localhost:4000`


### License

[MIT](/LICENSE)
