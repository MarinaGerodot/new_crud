<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 *@UniqueEntity("username")
 */

class User implements UserInterface, \Serializable
{
   /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"individual", "default"})
     * @Assert\Length(max=255, groups={"individual", "default"})
     */
    private $username;


    /**
     * @ORM\Column(name="password", type="string", length=500)
     * @Assert\NotBlank(groups={"individual", "company", "default"}))
     * @Assert\Length(min=3, groups={"individual", "default"})
     */
    private $password;


   /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
    
    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"individual", "company", "default"}))
     * @Assert\Email(groups={"individual", "company", "default"}))
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
	
	/**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Сброс прав пользователя.
     */
    public function eraseCredentials()
    {
 
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(mt_rand(), true));
        
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {	
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
			$this->email,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
			$this->email,
            $this->salt
        ) = unserialize($serialized);
    }


}

