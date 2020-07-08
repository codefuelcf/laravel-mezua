# Laravel Sms Package - Mezua

## Table of Content
- [About](##about)
- [Why Mezua?](##why-mezua)
- [Installation](##installation)
- [Basic Usage](##usage)
    - [Sms Api Usage](###sms-api-usage)
    - [Sms Usage](###sms-usage)
- [Contribution](##contribution)

---
## About
This is a simple laravel package for Sms management  

```php
public function sendSms()
{
    return Sms::create('Hello World', '1234567890')
              ->send();
}
```
That's how simple it makes sending smses

*Note: The package is currently in alpha development mode, so please contribute ideas and code to improve it*

---
## Why Mezua
The answer is simple, you can manage and use all your different Sms gateway's that require different settings with this one simple to use package 

---
## Installation
Follow these instructions to setup *codefuelcf/laravel-mezua* for your project using *composer* package manager
- Run `composer require codefuelcf/laravel-mezua` in the terminal
- Add the MezuaServiceProvider to you `config\app.php` providers list

```php
'providers' => [
    /*
    * Package Service Providers...
    */
    Codefuelcf\Mezua\MezuaServiceProvider::class
]
```

- Run `php artisan vendor:publish --provider="Codefuelcf\Mezua\MezuaServiceProvider"`
- Run `php artisan migrate` to generate all the required tables
- Add a command schedule in `app\Console\Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    /*
    *   Command Schedule for Mezua
    */
    $schedule->call('App\Http\Controllers\SmsController@sendQueued')
             ->everyMinnute();
}
```

- To use laravel scheduler add the following cron

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---
## Usage
Add `Codefuelcf\Mezua\Sms` to your controllers to perform Sms actions  
Add `Codefuelcf\Mezua\SmsApi` to your controllers to manage Sms Api's

```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Codefuelcf\Mezua\Sms;
use Codefuelcf\Mezua\SmsApi;

class TestController extends Controller
{
    //
}
```

### Sms Api Usage
Make sure to include `Codefuelcf\Mezua\SmsApi` in your controller  
- To create a Sms Api, use the `create` method

```php
public function createSmsApi()
{
    // Name for the Api
    $name = 'Test Sms Api';

    // Slug for the Api, this parameter can be left empty for auto generation
    $slug = 'test-sms-api';

    // Url for the Api
    $url = 'https://api.codefuel.cf/sms/';

    // What type of Api is it (POST | GET)
    $type = 'POST';

    // Data which is to be sent in the body
    $data = [
        // Use {{ message }} and {{ receiverPhoneNumber }} as values for options that need to be the message and phone number
        'messgage' => '{{ message }}',
        'phone' => '{{ receiverPhoneNumber }}'
    ];

    // Headers that need to sent with the request, this parameter can be left empty if not required
    $headers = [
        //
    ];

    $newSmsApi = SmsApi::create($name, $slug, $url, $type, $data, $headers);

    return $newSmsApi;
}
```

- To show a Sms Api, use `show` method

```php
public function showSmsApi($identifier)
{
    // The identifier can be the id or slug of the sms Api
    $smsApi = SmsApi::show($identifier);

    return view('showSmsApi', compact('smsApi'));
}
```

- To update a Sms Api, use `update` method

```php
public function updateSmsApi()
{
    $identifier = 'test-sms-api';

    $attributes = [
        'type' => 'GET',
        'headers' => [
            'X-XSRF-TOKEN' => csrf_token()
        ]
    ];

    // The identifier can be the id or slug of the sms Api and attributes need to be an array with columns that are to be updated
    $smsApi = SmsApi::update($identifier, $attributes);

    return redirect('/sms-apis');
}
```

- To delete a Sms Api, use `delete` method

```php
public function deleteSmsApi()
{
    $identifier = 1;

    // The identifier can be the id or slug of the sms Api
    $smsApi = SmsApi::delete($identifier);

    return redirect('/sms-apis');
}
```

### Sms Usage
Make sure to include `Codefuelcf\Mezua\Sms` in your controller and make sure to create a *Sms Api* before sending smses  
- To simply send a sms

```php
public function smsFunctionality()
{
    $message = 'Hello World';

    $receiverPhoneNumber = '1234567890';

    // This will add sms to the queue and will automatically process if mezua command schedule is set and cron enabled for your laravel application
    $sms = Sms::create($message, $receiverPhoneNumber)
              ->send();

    return $sms;
}
```

- To send sms a using a particular gateway

```php
public function smsFunctionality()
{
    $message = 'Hello World';

    $receiverPhoneNumber = '1234567890';

    // The identifier can be the id or slug of the sms Api
    $identifier = 'test-sms-api';

    // This will add sms to the queue and will automatically process if mezua command schedule is set and cron enabled for your laravel application
    // The sms will be sent using the specified gateway API
    $sms = Sms::create($message, $receiverPhoneNumber)
              ->usingGateway($identifier)
              ->send();

    return $sms;
}
```

- To send a sms instantly without waiting for the queue

```php
public function smsFunctionality()
{
    $message = 'Hello World';

    $receiverPhoneNumber = '1234567890';

    // This will instantly send the sms
    $sms = Sms::create($message, $receiverPhoneNumber)
              ->sendNow();

    return $sms;
}
```

- To send smses that are in queue

```php
public function smsFunctionality()
{
    // This will send queued smses (Default limit 250 smses)
    $smses = Sms::sendQueued(); // It takes 1 parameter that can specify the limit

    return $smses;
}
```

*Note: `sendQueued` method can be used to manually send smses that are in queue, in case you are not using cron job*

- To get total sms count

```php
public function smsFunctionality()
{
    $totalSmses = Sms::totalCount();

    return $totalSmses;
}
```

- To get total queued sms count

```php
public function smsFunctionality()
{
    $queuedSmses = Sms::totalQueuedCount();

    return $queuedSmses;
}
```

- To get total sent sms count

```php
public function smsFunctionality()
{
    $sentSmses = Sms::totalSentCount();

    return $sentSmses;
}
```

---
## Contribution
To contribute to this repository please open pull requests and if you have any ideas or features that you would like to see in this package, feel free to reach me at [codefuelcf@gmail.com](mailto:codefuelcf@gmail.com)  
While commiting changes to the repo please use the pattern below for commit messages:  
- For fixes, message should be ` FIXED - //Whatever you have fixed// `
- For updates, message should be ` UPDATED - //Whatever you have updated// `
- For new features, message should be ` CREATED - //Whatever you have created// `
- For removals, message should be ` REMOVED - //Whatever you have removed// `
- For improvements, message should be ` IMPROVED - //Whatever you have improved// `