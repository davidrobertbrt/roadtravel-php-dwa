<?php
class Discount{

    private $id;
    private $factor;
    private $used;
    
    public function __construct($id, $factor,$used)
    {
        $this->id = $id;
        $this->factor = $factor;
        $this->used = $used;
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
     * Get the value of factor
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set the value of factor
     */
    public function setFactor($factor): self
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get the value of used
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set the value of used
     */
    public function setUsed($used): self
    {
        $this->used = $used;

        return $this;
    }

    public function toArray()
    {
        return array(
            'factor'=>$this->factor,
            'used'=>$this->used
        );
    }
}