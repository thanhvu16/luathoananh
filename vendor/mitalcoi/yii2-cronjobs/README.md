Yii2 cronjobs extension
========
Easiest way to put crontab on your console scripts.

This extension is based on [this](https://github.com/Yiivgeny/Yii-PHPDocCrontab).
Thanks [Yiivgeny](https://github.com/Yiivgeny).

But with a few changes:
- Work eith yii2
- Set config in params (not in phpDocs).


Installation
------------

- **Step 1:** The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mitalcoi/yii2-cronjobs "*"
```

or add

```
"mitalcoi/yii2-cronjobs": "*"
```

to the require section of your `composer.json` file.
- **Step 2:** Set aliase  @runnerScript in console config. This absolutely path to runner script
Change path to runner script as your project. 
```
Yii::setAlias('@runnerScript', dirname(__DIR__) . '/yii');
```
- **Step 3:** Add to console config:
```
'controllerMap' => [
       'cron' => [
           'class' => 'mitalcoi\cronjobs\CronController',
           'cronJobs' =>[
        	'test/example1' => [
            		'cron'      => '* * * * *',            
        	],
		'test/example2' => [
            		'cron'      => '10 * * * *',            
        	],

    	    ],
       ],
   ],
```
- **Step 4:**  Add task to system scheduler (cron on unix, task scheduler on windows) to run every minute:

```sh
* * * * * /path/to/yii/application/protected/yiic cron
```

You can point any settings from [this](https://github.com/Yiivgeny/Yii-PHPDocCrontab/blob/master/examples/ExampleRuCommand.php).


