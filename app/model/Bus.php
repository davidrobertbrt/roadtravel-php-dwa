<?php

class Bus
{
    private $id;
    private $nrSeats;

    public function __construct($id,$nrSeats)
    {
        $this->id = $id;
        $this->nrSeats = $nrSeats;
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
     * Get the value of nrSeats
     */
    public function getNrSeats()
    {
        return $this->nrSeats;
    }

    /**
     * Set the value of nrSeats
     */
    public function setNrSeats($nrSeats): self
    {
        $this->nrSeats = $nrSeats;

        return $this;
    }

    public function toArray()
    {
        return array(
            'nrSeats' => $this->nrSeats
        );
    }
}