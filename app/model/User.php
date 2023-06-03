<?php

class User{

    private $id;
    private $emailAddress;
    private $firstName;
    private $lastName;
    private $dateOfBirth;
    private $phoneNumber;
    private $address;

    public function __construct() {}

    public static function loadByParams($id,$emailAddress,$firstName,$lastName,$dateOfBirth,$phoneNumber,$address)
    {
        $inst = new User();
        $inst->id = $id;
        $inst->emailAddress = $emailAddress;
        $inst->firstName = $firstName;
        $inst->dateOfBirth = $dateOfBirth;
        $inst->lastName = $lastName;
        $inst->phoneNumber = $phoneNumber;
        $inst->address = $address;

        return $inst;
    }

    public static function constructNoId($emailAddress,$firstName,$lastName,$dateOfBirth,$phoneNumber,$address)
    {
        $inst = new User();
        $inst->emailAddress = $emailAddress;
        $inst->firstName = $firstName;
        $inst->dateOfBirth = $dateOfBirth;
        $inst->lastName = $lastName;
        $inst->phoneNumber = $phoneNumber;
        $inst->address = $address;

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
     * Get the value of emailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set the value of emailAddress
     */
    public function setEmailAddress($emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     */
    public function setFirstName($firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     */
    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of dateOfBirth
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set the value of dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get the value of phoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the value of phoneNumber
     */
    public function setPhoneNumber($phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the value of address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     */
    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    public function toArray()
    {
        return array(
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dateOfBirth' => $this->dateOfBirth,
            'phoneNumber' => $this->phoneNumber,
            'address' => $this->address,
            'emailAddress' => $this->emailAddress
        );
    }
}