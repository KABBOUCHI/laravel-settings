# Advanced Settings Manager for Laravel

## Installation

You can install the package via composer:

```bash
composer require kabbouchi/laravel-settings:dev-master
```

## Usage

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
                    ->help('lorem ipsum....'),
                TextArea::make('Site Description'),
            ];
        }),
        Group::make('Contact Us', function () {
            return [
                Text::make('Phone Number', 'phone')
                    ->setTranslatable(false)
            ];
        })->setKey('contact-us')
    ];
});
```
## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
