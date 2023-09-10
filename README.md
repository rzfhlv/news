<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirements

- Docker v1.0.28 or higher
- Git
- Composer v2.5.4
- If you not prefer with docker this application build on:
    + ```Laravel v10```
    + ```MySQL v8```
    + ```Redis v6.2```
    + ```Nginx v1.24```
    + ```PHP v8.2```
    + ```Composer v2.5.4```
    + ```PHP Ext for Redis```
    + ```Supervisor```

## How to Install

- Clone this repo to your local machine ```$ git clone https://github.com/rzfhlv/news.git```
- Go to root directory for this project ```$ cd <path_your_local_repo>```
- Copy env file ```$ cp .env.example .env```
- For Running the application ```$ make up```
- For first time init application (and your whole setup done) ```$ make install```
- This project ready to use
- If you using docker you don't need to setup anymore your queue under the hoood handle by docker container with supervisor, you just go to check the API
- For stop the application ```$ make down```
