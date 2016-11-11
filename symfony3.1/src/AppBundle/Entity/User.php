<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/18/2016
 * Time: 3:55 PM
 */

namespace AppBundle\Entity;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="It look like you already have an account")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $firstName;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(
     *      min = 6,
     *      max = 12,
     *      minMessage = "Password must be at least {{ limit }} characters long",
     *      maxMessage = "Password cannot be longer than {{ limit }} characters"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];


    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     *Assert\NotBlank(message="Please, upload the user image file.")
     */
    protected $photo;

    public function __construct()
    {
        $this->setCreationDate(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
       return $this->email;
    }

    public function getRoles()
    {
        //if user have no role add user role
        $roles = $this->roles;
        if(!in_array('ROLE_USER', $roles)){
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        // guarentee that the entity looks dirty to Doctrine
        // when changing the plainPassword
        $this->password = null;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }


    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }





}