# Requirements

You need

- Composer
- PHP
- Laravel
- Any Database

# Starting the Website

To start the Website, please first connect it to a database. You can change the settings in the .env File. After that use the following command

```
php artisan migrate
```

This command will create the database for the website.

You can now start the project by using the command:

```
php artisan serve (-host <ip> -p <port>)
```

The host and port are optional, that why i have used ().
