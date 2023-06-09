<?php

class Role
{
    private $id;
    private $userId;
    private $role;

    public function __construct() {}

    public static function load($id,$userId,$role)
    {
        $inst = new Role();

        $inst->id = $id;
        $inst->userId = $userId;
        $inst->role = $role;

        return $inst;
    }

    public static function constructNoId($userId,$role)
    {
        $inst = new Role();

        $inst->userId = $userId;
        $inst->role = $role;

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
    public function setId($id)
    {
        $this->id = $id;
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
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the value of role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    public function toArray()
    {
        return array(
            'userId' => $this->userId,
            'role' => $this->role
        );
    }
}