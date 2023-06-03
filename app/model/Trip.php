<?php

class Trip
{
    private $id;
    private $bus;
    private $locationStartId;
    private $locationEndId;
    private $dateTimeStart;
    private $dateTimeEnd;

    public function __construct($id,$busId, $locationStartId, $locationEndId,$dateTimeStart,$dateTimeEnd)
    {
        $this->id = $id;
        $this->busId = $busId;
        $this->locationStartId = $locationStartId;
        $this->locationEndId = $locationEndId;
        $this->dateTimeStart = $dateTimeStart;
        $this->dateTimeEnd = $dateTimeEnd;
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
     * Get the value of busId
     */
    public function getBusId()
    {
        return $this->busId;
    }

    /**
     * Set the value of busId
     */
    public function setBusId($busId): self
    {
        $this->busId = $busId;

        return $this;
    }

    /**
     * Get the value of locationStartId
     */
    public function getLocationStartId()
    {
        return $this->locationStartId;
    }

    /**
     * Set the value of locationStartId
     */
    public function setLocationStartId($locationStartId): self
    {
        $this->locationStartId = $locationStartId;

        return $this;
    }

    /**
     * Get the value of locationEndId
     */
    public function getLocationEndId()
    {
        return $this->locationEndId;
    }

    /**
     * Set the value of locationEndId
     */
    public function setLocationEndId($locationEndId): self
    {
        $this->locationEndId = $locationEndId;

        return $this;
    }

    /**
     * Get the value of dateTimeStart
     */
    public function getDateTimeStart()
    {
        return $this->dateTimeStart;
    }

    /**
     * Set the value of dateTimeStart
     */
    public function setDateTimeStart($dateTimeStart): self
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    /**
     * Get the value of dateTimeEnd
     */
    public function getDateTimeEnd()
    {
        return $this->dateTimeEnd;
    }

    /**
     * Set the value of dateTimeEnd
     */
    public function setDateTimeEnd($dateTimeEnd): self
    {
        $this->dateTimeEnd = $dateTimeEnd;

        return $this;
    }

    public function toArray()
    {
        return array(
            'busId'=>$this->busId,
            'locationStartId' => $this->locationStartId,
            'locationEndId' => $this->locationEndId,
            'dateTimeStart' => $this->dateTimeStart,
            'dateTimeEnd' => $this->dateTimeEnd
        );
    }
}