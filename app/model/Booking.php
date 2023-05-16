<?php

class Booking
{
    private $id;
    private $tripId;
    private $userId;
    private $numOfPersons;
    private $price;
    private $datePurchase;

    public function __construct($id, $tripId,$userId,$numOfPersons,$price,$datePurchase)
    {
        $this->id = $id;
        $this->tripId = $tripId;
        $this->userId = $userId;
        $this->numOfPersons = $numOfPersons;
        $this->price = $price;
        $this->datePurchase = $datePurchase;
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
     * Get the value of tripId
     */
    public function getTripId()
    {
        return $this->tripId;
    }

    /**
     * Set the value of tripId
     */
    public function setTripId($tripId): self
    {
        $this->tripId = $tripId;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of numOfPersons
     */
    public function getNumOfPersons()
    {
        return $this->numOfPersons;
    }

    /**
     * Set the value of numOfPersons
     */
    public function setNumOfPersons($numOfPersons): self
    {
        $this->numOfPersons = $numOfPersons;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of datePurchase
     */
    public function getDatePurchase()
    {
        return $this->datePurchase;
    }

    /**
     * Set the value of datePurchase
     */
    public function setDatePurchase($datePurchase): self
    {
        $this->datePurchase = $datePurchase;

        return $this;
    }

    public function toArray()
    {
        return array(
            'tripId' => $this->tripId,
            'userId' => $this->userId,
            'numOfPersons'=>$this->numOfPersons,
            'price'=>$this->price,
            'datePurchase'=>$this->datePurchase
        );
    }
}