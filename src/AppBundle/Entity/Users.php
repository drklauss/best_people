<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class Users
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
     * @ORM\Column(name="nickname", type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *   min = 3,
     *   max = 50
     * )
     */

    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *   min = 4,
     *   max = 50
     * )
     * @Assert\Regex(
     *    pattern="/(\d+[a-z]+|[a-z]+\d+)/i",
     *    message="Password must contain letters (a-z) and digits(0-9)"
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="isFemale", type="boolean")
     * * @Assert\NotBlank
     */
    private $isFemale;

    /**
     * @var string
     *
     * @ORM\Column(name="avatarLink", type="string", length=255, nullable=true)
     */
    private $avatarLink;

    /**
     * @ORM\OneToMany(targetEntity="Votes", mappedBy="toUserId")
     */

    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="Messages", mappedBy="toUserId")
     */

    private $messages;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nickname
     *
     * @param string $nickname
     * @return Users
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
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
     * Set isFemale
     *
     * @param boolean $isFemale
     * @return Users
     */
    public function setIsFemale($isFemale)
    {
        $this->isFemale = $isFemale;

        return $this;
    }

    /**
     * Get isFemale
     *
     * @return boolean 
     */
    public function getIsFemale()
    {
        return $this->isFemale;
    }

    /**
     * Set avatarLink
     *
     * @param string $avatarLink
     * @return Users
     */
    public function setAvatarLink($avatarLink)
    {
        $this->avatarLink = $avatarLink;

        return $this;
    }

    /**
     * Get avatarLink
     *
     * @return string 
     */
    public function getAvatarLink()
    {
        return $this->avatarLink;
    }

    /**
     * Add votes
     *
     * @param \AppBundle\Entity\Votes $votes
     * @return Users
     */
    public function addVote(\AppBundle\Entity\Votes $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \AppBundle\Entity\Votes $votes
     */
    public function removeVote(\AppBundle\Entity\Votes $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Add messages
     *
     * @param \AppBundle\Entity\Messages $messages
     * @return Users
     */
    public function addMessage(\AppBundle\Entity\Messages $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \AppBundle\Entity\Messages $messages
     */
    public function removeMessage(\AppBundle\Entity\Messages $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
