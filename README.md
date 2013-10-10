Dlin Symfony Keen Bundle
=========

Dlin Keen Bundle is Symfony2 wrapper bundle for the 'Keen.IO' PHP library:


This Sentry Bundle provides a configurable service to work with Keen.IO



Version
--------------

0.9



Installation
--------------


Installation using [Composer](http://getcomposer.org/)

Add to your `composer.json`:


    json
    {
        "require" :  {
            "dlin/keen-bundle": "dev-master"
        }
    }


Enable the bundle in you AppKernel.php


    public function registerBundles()
    {
        $bundles = array(
        ...
        new Dlin\Bundle\SentryBundle\DlinKeenBundle(),
        ...
    }


Configuration
--------------

The DSN url must be provided in the config.xml file. For example:

    #app/config/config.yml

    dlin_keen:
        project_id: 12345
        read_key: xxxxxxxxxxx
        write_key: xxxxxxxxx


Usage
--------------

Geting the service in a controller

    $service =  $this->get('dlin.keen_service');

Getting the service in a ContainerAwareService

    $service = $this->container->get('dlin.keen_service');

Sending an event to Keen.IO with data

    $eventCollectionName = "purchases";

    $eventData = array('porduct_id'=>1, 'quantity'=>2, 'amount'=>120);

    $service->fireEvent($eventCollectionName, $eventData);


Sending an event in an OOP way.


    //create an event object with public properties
    $eventObject = new MyPurchaseEvent();
    $eventObject->productId = 1;
    $eventObject->quantity = 2;
    $eventObject->amount = 120;


    $service->fireEventObject($eventObject); //this is equivalent to the last fireEvent call



    //You can defined your own event class
    Class MyPurchaseEvent{

       //Public properties will be send as event data
       public $productId;
       public $quantity;
       public $amount;

       // Procted and private properties are ignored
       protected $customerAddress;
       private $customerGender;

       //By default, the event collection name will be the class name in camelCase (e.g. myPurchaseEvent)
       //You can specify the collection name by defining a public method named 'getCollectionName'
       public function getCollectionName(){
          return 'purchases';
       }

    }

 

License
-

MIT

*Free Software, Yeah!*


