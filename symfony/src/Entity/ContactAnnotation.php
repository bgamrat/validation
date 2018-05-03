<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"email"},
 *     message="You've already sent us a message, be patient"
 * )
 */
class ContactAnnotation
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false, unique=false)
     * @Assert\Regex(
     *     pattern="/^\w{2,} \w{2,}$/",
     *     htmlPattern="\w{2,} \w{2,}",
     *     message="Invalid name",
     *     match=true)
     */
    protected $name;
    /**
     * @ORM\Column(name="email", type="text", nullable=false, unique=false)
     * @Assert\Email(
     *     message = "Invalid email",
     *     checkMX = false)
     */
    protected $email;
    /**
     * @ORM\Column(name="message", type="text", nullable=false, unique=false)
     * @Assert\Regex(
     *     pattern="/^[a-z !@#$%^\x26*()-_+=;:\x27\x22?,\.]{10,512}$/",
     *     htmlPattern = "/^[a-z !@#$%^\x26*()-_+=;:\x27\x22?,\.]{10,512}$/i",
     *     message = "Invalid message",
     *     match=true)
     *
     * The htmlPattern includes the slashes and flags because the message is
     * a text type and will be entered using a textarea.  Textareas do not
     * support pattern attributes.
     */
    protected $message;

    public function setId( Int $id )
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName( String $name )
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail( String $email )
    {
        $this->email = $email;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage( String $message )
    {
        $this->message = $message;
    }

}
