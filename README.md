Cake-Sentry
===========

**Cake-Sentry** is an error handler plugged on [Sentry](http://www.getsentry.com) - [docs](http://sentry.readthedocs.org/en/latest/quickstart/index.html#setting-up-an-environment)

Installation
------------
**Note if you don't install from composer you will have to manually download the raven component and install it before proceeding to step 2.**

1. Install Sentry Plugin into your CakePHP project with composer :
<pre>
    // composer.json

    // …

    "require": {
      // …
      "snakano/cake-sentry": "*"
    },

    // …
</pre>


2. Load the cake-sentry Plugin in your *bootstrap.php* :
```php
	CakePlugin::load('Sentry');
```


3. Configure the error handler in your *core.php* :
```php
	App::uses('SentryErrorHandler', 'Sentry.Lib');

	Configure::write('Sentry', array(
		'production_only' => false, // true is default value -> no error in sentry when debug
		'PHP' => array(
			'server'=>'http://your-sentry-DSN-for-PHP'
		),
		'javascript' => array(
			'server'=>'http://your-sentry-DSN-for-javascript'
		)
	));

	Configure::write('Error', array(
		'handler' => 'SentryErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));

	Configure::write('Exception', array(
		'handler' => 'SentryErrorHandler::handleException',
		'renderer'=>'ExceptionRenderer'
	));
```

4. Use Sentry as logger :
```php
	CakeLog::config('default', array('engine' => 'Sentry.SentryLog'));
```

5. include ravenjs and init script in the default layout :
```php
	<?php
	echo $this->Html->script('jquery');
	echo $this->Html->script('ravenjs-min');
	?>
	<script type="text/javascript">
		$(function () {
			<?php echo $this->element('Sentry.raven-js'); ?>
		});
	</script>
```
