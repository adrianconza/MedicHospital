# Medic Hospital

## ✍️ Description
Hospital management system, using laravel and mysql.

> ### Note to run the application:
> * Copy the `.env.example` file to `.env`
> 
> #### Data Base
> * You need to create a database
> * Configure the connection for the database
> ```bash
> DB_CONNECTION=
> DB_HOST=
> DB_PORT=
> DB_DATABASE=
> DB_USERNAME=
> DB_PASSWORD=
> ```
> 
> #### Email
> * To send emails you need to configure a mailing server, as a suggestion you can use sparkpost
> * Configure the connection for the mailing server
> ```bash
> MAIL_MAILER=
> MAIL_HOST=
> MAIL_PORT=
> MAIL_USERNAME=
> MAIL_PASSWORD=
> MAIL_ENCRYPTION=
> MAIL_FROM_ADDRESS=
> MAIL_FROM_NAME=
> ```

## ⚙️ Installation

```bash
# install dependencies
$ composer install

# install npm dependencies
$ npm run install
```

## 🚀 Running with laravel server
```bash
$ php artisan serve
```

## 🐳 Running with docker
```bash
# to run the project
$ docker-compose up

# to run the project in the background
$ docker-compose up -d

# to stop the project
$ docker-compose down
```

> ### Note to run commands with the docker:
> To run the commands in the docker container you need 
> to do it using the following `docker-compose exec`
> followed by the name of the service with which it was created
> in the docker-compose file
>
> For this case the command would be: `docker-compose exec app`
> ```bash
> # run migrate all tables in the container docker
> $ docker-compose exec app php artisan migrate
>
> # generate buil of css and js in the container docker
> $ docker-compose exec app npm run dev
> ```


## 📦 Generate build of css and js
```bash
# development mode
$ npm run dev

# production mode
$ npm run prod
```
 
## 📑 Migrations
```bash
# migrate all tables
$ php artisan migrate

# migrate all tables with seeders
$ php artisan migrate --seed
```

## 🤝 Support
Support by [Adrian Conza](https://gitlab.com/adrianconza)

## 💬 Stay in touch
- Author - [Adrian Conza](http://adrianconza.com/)
