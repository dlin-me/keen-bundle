<?php


namespace Dlin\Bundle\KeenBundle\Service;

use KeenIO\Service\KeenIO;

class KeenService
{

    /**
     * Constructor
     *
     * @inheritdoc
     */
    function __construct($projectId, $writeKey, $readKey)
    {
        KeenIO::configure($projectId, $writeKey, $readKey);

    }

    /**
     * Fire an event with data encapsulated in an object.
     *
     * If $collectionName is not given, it will try to call getEventCollectionName method if defined, otherwise, the object class name is used;
     * All public properties of the given object is sent as the event data.
     *
     *
     * @param $eventObject
     */
    function fireEventObject($eventObject, $collectionName = null){
        $collectionName = $this->getCollectionName($eventObject, $collectionName);

        $data = get_object_vars($eventObject);

        $this->fireEvent($collectionName, $data);
    }

    /**
     * Fire an event with the given event collection name and data
     *
     * @param $collection
     * @param $data
     */
    function fireEvent($collection, $data){

        KeenIO::addEvent($collection, $data);
    }

    /**
     * Deduct the collection name to be used
     *
     * @param $object
     * @param $collectionName
     * @return mixed|string
     */
    public function  getCollectionName($object, $collectionName=null){
        if($collectionName == null){
            if(method_exists($object, 'getCollectionName')){
                $collectionName = call_user_func(array($object, 'getCollectionName')) ;
            }else{

                $fullClass = get_class($object);
                $class = explode('\\', $fullClass);
                $collectionName = lcfirst(array_pop($class));
            }
        }

        return $collectionName;

    }



}