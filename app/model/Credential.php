<?php

class Credential
{
    private $id;
    private $userId;
    private $password;

    public function construct() {}

    public static function loadByParams($id,$userId,$password)
    {
        $inst = new Credential();
        $inst->id = $id;
        $inst->userId = $userId;
        $inst->password = $password;

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
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function toArray()
    {
        return array(
            'userId' => $userId,
            'password' => $password
        );
    }
}