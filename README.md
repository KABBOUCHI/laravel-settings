# Advanced Settings Manager for Laravel

## Installation

You can install the package via composer:

```bash
composer require kabbouchi/laravel-settings
```

Publish its assets:

```bash
php artisan vendor:publish --tag=laravel-settings-components
php artisan vendor:publish --tag=laravel-settings-migrations
```

Register the vue component:

```diff
require('./bootstrap');

window.Vue = require('vue');

+import SettingsManager from './components/laravel-settings/SettingsManager.vue';

+Vue.component('settings-manager', SettingsManager);

const app = new Vue({
    el: '#app'
});
```
Add the `settings-manager` in your blade file

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <settings-manager></settings-manager>
</div>
@endsection

```
## Usage (in AppServiceProvider)

``` php
Settings::auth(function () {
    return auth()->check();
});

Settings::languages(function () {
    return ['en' => 'English', 'ar' => 'Arabic'];
});

Settings::fields(function (Request $request) {
    return [
        Group::make('General', function () {
            return [
                Text::make('Site Name')
                    ->setTranslatable(true)
                    ->help('lorem ipsum....'),
                TextArea::make('Site Description')->setTranslatable(true),
            ];
        }),
        Group::make('Contact Us', function () {
            return [
                Text::make('Phone Number', 'phone')
            ];
        })->setKey('contact-us')
    ];
});
```
## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
