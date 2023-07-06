# PHP OwoFramework Application

This repository provides a custom PHP framework from scratch using POO design patterns and [clean code recommendations](https://github.com/jupeter/clean-code-php).

This framework provides also Progressive Web Apps ([PWA](https://web.dev/progressive-web-apps/)) services.

## Disclaimer

Watch out, this project is meant to show how to build a PHP framework from scratch using POO design patterns and clean code recommendations. Be aware that this code is only meant for learning purposes and should probably not go to production as-is.

Improvements and pull requests are welcome.

## Run

To run this framework appliaction, you need to clone it and install dependencies:

```
git clone https://github.com/JohnCOD4BO/OwoFramework.git
cd OwoFramework-main/
composer install
```

You can then run the web application using PHP's built-in server:

```
php -S 0.0.0.0:8080 -t public/
```

The web application is running at [http://localhost:8080](http://localhost:8080/).

## Architecture

- The application folder [application/](application/) is used to customize the configuration and implementation of the application according to the specifications.
- The framework folder [framework/](framework/) is used for configuration initialization and implementation of the framework sources and resources.
- The public folder [public/](public/) is used for implementation and definition of application launch behavior and public sources and resources.
- The temporary folder [temporary/](temporary/) is used to store temporary sources and resources required or generated by the application.
- The thirdparty folder [thirdparty/](thirdparty/) is used for importing sources external to the framework and the application in order to provide code origins visibility.

## Recommendations

- PHP version should be >= 7.4
