<?php


namespace Dlin\Bundle\KeenBundle\Service;

use KeenIO\Client\KeenIOClient;


class KeenService
{

    /**
     * This is a holder of events scheduled to  fire
     * @var array
     */
    protected  $eventSchedule ;

    /**
     * @var  \KeenIO\Client\KeenIOClient
     */
    private $client;

    /**
     * Constructor
     *
     * @inheritdoc
     */
    public function __construct($projectId, $writeKey, $readKey)
    {


        $this->client = KeenIOClient::factory(array(
            'projectId' => $projectId,
            'writeKey' => $writeKey,
            'readKey' => $readKey
        ));


        $this->eventSchedule = array();
    }


    /**
     *
     * This return the initialized client.
     * Useful for making query, for example
     *
     * @return \Guzzle\Service\Client|KeenIOClient
     *
     */
    public function getClient(){
        return $this->client;
    }



    /**
     * Fire all events in schedule
     */
    public function fireScheduledEvents(){

        $this->client->addEvents(array('data'=>$this->eventSchedule));
        $this->eventSchedule = array();

    }


    /**
     * Get an array of event in the schedules
     *
     * @param $collectionName
     */
    public function getScheduledEvents($collectionName){

        return array_key_exists($collectionName, $this->eventSchedule) ? $this->eventSchedule[$collectionName] :array();
    }



    /**
     * Cancel an event in the schedule using the collection name
     *
     * @param $collectionName
     */
    public function cancelScheduledEvents($collectionName) {
        unset($this->eventSchedule[$collectionName]);
    }


    /**
     * Schedule an event to fire later. i.e. at the end of the script execution
     *
     * @param $eventObject
     * @param null $collectionName
     */
    public function scheduleEventObject($eventObject, $collectionName =null){
        $this->scheduleEvent($this->getCollectionName($eventObject, $collectionName),get_object_vars($eventObject) );
    }


    /**
     *
     * * Schedule an event to fire later. i.e. at the end of the script execution
     *
     * @param $collectionName
     * @param $data
     */
    public function scheduleEvent($collectionName, $data){

        if(!isset($this->eventSchedule[$collectionName])){
            $this->eventSchedule[$collectionName] = array();
        }
        $this->eventSchedule[$collectionName][] = $data;


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

        $this->client->addEvent($collectionName, array('data'=> $data));
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