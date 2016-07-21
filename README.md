# cron-event
Symfony2 bundle to set and run cron-based timers and events. It generates events inside the application based on timers stored in DB.

Currently bundle supports MongoDB only.

## Steps to install and check

1. run command in project root folder: `composer require litvinab/cron-event`

2. add `new Litvinab\Bundle\CronEventBundle\CronEventBundle()` in `AppKernel.php`

3. setup cron task: `php app/console cron:run` for each minute

4. for testing purposes add bundle test routes to `routing.yml`: 

```php
cron:
    resource: "@CronEventBundle/Resources/config/routing.yml"
    prefix:   /cron
```    
5. To add test timer (1 minute timer) and event go to `http://your-domain/cron/`

6. Added schedules will be displayed on this page: `http://your-domain/cron/show`
`status` fields should be `unexpired` 

7. Refresh `http://your-domain/cron/show` page after 1-2 minutes after point #5. 
`status` fields should be `expired` it means that bundle works right. 


!! Do not forget to remove test routes from `routing.yml`. It's not secure to leave it there. 

## Supported event types

`timer` - event in application will be triggered after N milliseconds.

`event` - event in application will be triggered in specified date and time.


## How to use

### 1. Set event in your code

In controller:
```php
// get cron manager
$cronManager = $this->get('cron_event.manager');

// set timer with: human name, name, period 
// name of the symfony event: `cron_event.` + name
$timer = $cronManager->setTimer('My timer', 'test_timer', 7200);
```

### 2. Add cron event subscriber

Of course you are able to create event listeners if you wish.

services.yml:
```php
services:
     app.subscriber.cron:
          class: AppBundle\EventSubscriber\CronSubscriber
          calls:
            - [setLogger, [@cron_event.logger]]
          tags:
            - { name: kernel.event_subscriber }
```


/YourBundle/EventSubscriber/CronSubscriber.php:
```php
<?php
namespace AppBundle\EventSubscriber;

use Litvinab\Bundle\CronEventBundle\Events\CronEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Class CronSubscriber
 */
class CronSubscriber implements EventSubscriberInterface
{
    /**
     * @var Cron Logger
     */
    private $logger;

    /**
     * Set cron logger
     *
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'cron_event.test_timer' => array('onCronTestEvent', 0)
        );
    }

    /**
     * Test event
     *
     * @param CronEvent $cronEvent
     */
    public function onCronTestEvent(CronEvent $cronEvent)
    {
        // confirm that event is executed
        $this->logger->addInfo('onCronTestEvent');

        // delete cron event from DB
        $cronEvent->delete();
    }
} 
```

### 3. Check logs

CronEvent bundle providing it's own logger. Service name of logger is `cron_event.logger`.

`onCronTestEvent` string should be appeared in `app/logs/cron.log` file after 2-3 minutes.

