<?php

class Location {
    private $id;
    private $name;
    private $longitude;
    private $latitude;

    public function __construct() {}

    public static function loadByParams($id,$name,$longitude,$latitude)
    {
        $inst = new Location();
        $inst->id = $id;
        $inst->name = $name;
        $inst->longitude = $longitude;
        $inst->latitude = $latitude;

        return $inst;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of longitude
     */
    public function setLongitude($longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the value of latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the value of latitude
     */
    public function setLatitude($latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getGeopos()
    {
        return array(
            'latitude'=> $this->latitude,
            'longitude'=> $this->longitude
        );
    }

    public function toArray()
    {
        return array(
            'name'=>$this->name,
            'longitude'=>$this->longitude,
            'latitude'=>$this->latitude  
        );
    }
}