# RocketJar Laravel Application

This is the main application for RocketJar website. Laravel has been chosen to build the application with VueJS in the frontend. Frontend has been decoupled from the backend so that all operations are performed via an API and later down the line the same API can be used for mobile applications or third party access.

## Requirements
* PHP 7.1+
* MySQL 5.7+ (8 preferred)
* Nginx or Apache server
* All requirements of [Laravel 5.8](https://laravel.com/docs/5.8/installation#server-requirements)

## Installation
* Clone the repo
* `composer install`
* Copy `.env.example` to `.env` and populate it with the environment credentials
* `php artisan migrate`
* `php artisan db:seed`

## Third Party Libraries/Packages used

Following are the third party packages used for backend and frontend

### Laravel packages (backend)
* Laravel Passport : https://github.com/laravel/passport - For API authentication
* Laravel Permission :  https://github.com/spatie/laravel-permission - For various user roles and permissions
* League Flysystem : https://github.com/thephpleague/flysystem-aws-s3-v3 - For S3 cloud storage
* Stripe : https://github.com/stripe/stripe-php - For Stripe payments

### Frontend packages (JS and CSS)
* Vue Smooth Scroll : https://github.com/Teddy-Zhu/vue-smoothscroll - Smooth scrolling
* Vue Color : https://www.npmjs.com/package/vue-color and https://codepen.io/Brownsugar/pen/NaGPKy - For Color picker
* Vue FlatPickr Component: https://www.npmjs.com/package/vue-flatpickr-component - For DateTime Picker
* VueRouter
* Moment JS: https://momentjs.com - For Date and Time manipulations
* MomentTimezone: https://momentjs.com/timezone/ - For working with timezones
* Vue-sweetalert2: https://www.npmjs.com/package/vue-sweetalert2
* Vue Select: https://vue-select.org/guide/install.html
* Copy Url to clipboard: https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js
* vue-simplemde: https://www.npmjs.com/package/vue-simplemde and https://simplemde.com/ - Markdown editor
* vue-toastr: http://s4l1h.github.io/vue-toastr - to show Toast notifications
* vue-trend-chart: https://github.com/dmtrbrl/vue-trend-chart - to show trend chart
* markdown-it: https://github.com/markdown-it/markdown-it - Render markdown
* cropper.js: https://fengyuanchen.github.io/cropperjs/ - To crop images before upload
* vuejs-auto-complete: https://www.npmjs.com/package/vuejs-auto-complete -  For typeahead and autocomplete

### TODOs
* Solidify donation - refund if donation save fails
* Run whole donation creation in a DB transaction
* A reward or campaign cannot be deleted if it has donations
* Check all foreign models and make sure dependent models can't be deleted
