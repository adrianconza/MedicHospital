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

# generate build of css and js
$ npm run dev
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
