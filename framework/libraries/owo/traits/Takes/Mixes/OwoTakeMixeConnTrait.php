<?php

namespace framework\libraries\owo\traits\Takes\Mixes;


trait OwoTakeMixeConnTrait
{
    protected $conn = null;

    public function __construct($conn)
    {
        $this->setConn($conn);
    }

    public function setConn($conn): self
    {
        $this->conn = $conn;
        return $this;
    }

    public function getConn()
    {
        return $this->conn;
    }
}
