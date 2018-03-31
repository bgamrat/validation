<?php

namespace App\Entity;

class Contact
{

    /**
     * @var string
     */
    protected $name;
    /*
     * @var string
     */
    protected $email;
    /**
     * @var text
     */
    protected $message;

    public function getName()
    {
        return $this->name;
    }

    public function setName( $name )
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail( $email )
    {
        $this->email = $email;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage( $message )
    {
        $this->message = $message;
    }

}
