##Pick And Post

Pick and post allows you to schedule posts using the news items at [updatedapp.github.io](http://updatedapp.github.io/). This uses the Lumen framework and depends on the following libraries:

- ricardoper/twitteroauth
- guzzlehttp/guzzle
- jwage/purl
- nesbot/carbon

##How to Use

First you have to clone this project: 

```
git clone https://github.com/anchetaWern/pickandpost.git
```

Once cloned, install all the dependencies by using composer:

```
composer update
```

Create an `.env` file on the root directory of the project and add the following:

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=xxx53Il0axxxlNtZi1Wv2xxxQgX2xxx

APP_TITLE=PickAndPost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=Asia/Manila

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=pickandpost
DB_USERNAME=user
DB_PASSWORD=secret

CACHE_DRIVER=memcached
SESSION_DRIVER=cookie
QUEUE_DRIVER=database
```

Be sure to change the value for the following:

```
APP_KEY
APP_TIMEZONE
DB_USERNAME
DB_PASSWORD
```

The `QUEUE_DRIVER` is set to `database` so you also have to generate the migration for creating the table for storing the queued items:

```
php artisan queue:table
```

The command above will generate the migrations under the `database/migrations` directory. You can run the migrations after you create the database on a tool such as phpmyadmin or heidisql. The database name is `pickandpost` but you can also use a different name if you want, just be sure to also change it on your `.env` file. Once you have created the database and made sure that the database configuration that you have added on your `.env` file is correct, you can now run the migrations:

```
php artisan migrate
```

That will create all the tables required by the app.

Next go to the `config` directory and update each of the configuration files. In the facebook configuration, add a value for the app id, app secret and the oauth token of your facebook account or page. I don't know of any way of acquiring the `oauth_token` via the facebook developer site. And I don't plan on adding this functionality to the app. But you can check out my other project: [Ahead](http://github.com/anchetaWern/ahead) and find out how to acquire the `oauth_token` from there.

```
<?php
return [
    'id' => '',
    'secret' => '',
    'oauth_token' => ''
];
```

LinkedIn configuration is the same:

```
<?php
return [
    'id' => '',
    'secret' => '',
    'oauth_token' => ''
];
```

Twitter requires an additional `oauth_secret` configuration. The twitter developer site allows you to generate an access tokena and secret. So you can use that to easily add the values for these. Just remember that the `id` and `secret` is for the app and the `oauth_token` and `oauth_secret` is for the current user. 

```
<?php
return [
    'id' => '',
    'secret' => '',
    'oauth_token' => '',
    'oauth_secret' => ''
];
```

Then there's the times configuration:

```
<?php
return [
    'publishing_times' => [
        '7:30', '8:00', '8:30', '9:00', '9:30', '10:00', '10:30', 
        '11:00', '11:30', '12:00', 
        '13:00', '13:30', '14:00', '14:30',
        '16:00', '16:30', '17:00', '17:30',
        '19:30', '20:00', '20:30', '21:00', '21:30', '22:00'
    ]
];
```

This allows you to specify the times (in 24-hour format) in which the app is allowed to publish at any day. Note that the day is random in the next 30 days. And the time is also randomly selected based on what you put in this configuration file. 

Lastly you have to add the `queue:listen` artisan command to persistently run it. This allows Lumen to publish the posts that are due at any time. To do this you have to use something like supervisor. I've written [a tutorial about supervisor](http://wern-ancheta.com/blog/2014/08/15/getting-started-with-supervisor/) check that out if you're not sure how to use it. Here's the configuration file that I used on my machine:

```
[program:pickandpost]
command=php artisan queue:listen
directory=/home/wern/www
stdout_logfile=/home/wern/logs/pickandpost.log
redirect_stderr=true
```


##License

The MIT License (MIT) Copyright (c)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.