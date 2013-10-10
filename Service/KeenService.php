<?php


namespace Dlin\Bundle\KeenBundle\Service;

use KeenIO\Service\KeenIO;

class KeenService
{

    /**
     * This is a holder of events scheduled to  fire
     * @var array
     */
    protected  $eventSchedule ;




    /**
     * Constructor
     *
     * @inheritdoc
     */
    public function __construct($projectId, $writeKey, $readKey)
    {
        KeenIO::configure($projectId, $writeKey, $readKey);
        $this->eventSchedule = array();
    }


    /**
     * Fire all events in schedule
     */
    public function fireScheduledEvents(){

        foreach($this->eventSchedule as $event){
            $collectionName = reset($event);
            $data = array_pop($event);
            $this->fireEvent($collectionName, $data);
        }
        $this->eventSchedule = array();

    }


    /**
     * Get an array of event in the schedules
     *
     * @param $collectionName
     */
    public function getScheduledEvent($collectionName){
        $newArray = array();
        foreach($this->eventSchedule as $event){
            if( $collectionName == reset($event)){
                $newArray[] = $event;
            }
        }
        return $newArray;
    }



    /**
     * Cancel an event in the schedule using the collection name
     *
     * @param $collectionName
     */
    public function cancelScheduledEvents($collectionName) {
        $newArray = array();
        foreach($this->eventSchedule as $event){
            if( $collectionName != reset($event)){
                $newArray[] = $event;
            }
        }
        $this->eventSchedule = $newArray;
    }


    /**
     * Schedule an event to fire later. i.e. at the end of the script execution
     *
     * @param $eventObject
     * @param null $collectionName
     */
    public function scheduleEventObject($eventObject, $collectionName =null){
        $event = array($this->getCollectionName($eventObject, $collectionName), get_object_vars($eventObject));
        $this->eventSchedule[]  =$event;

    }


    /**
     *
     * * Schedule an event to fire later. i.e. at the end of the script execution
     *
     * @param $collectionName
     * @param $data
     */
    public function scheduleEvent($collectionName, $data){

        $event = array($collectionName, $data);
        $this->eventSchedule[]  =$event;

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
    public function fireEventObject($eventObject, $collectionName = null){
        $collectionName = $this->getCollectionName($eventObject, $collectionName);

        $data = get_object_vars($eventObject);

        $this->fireEvent($collectionName, $data);
    }

    /**
     * Fire an event with the given event collection name and data
     *
     * @param $collectionName
     * @param $data
     */
    public function fireEvent($collectionName, $data){

        KeenIO::addEvent($collectionName, $data);
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