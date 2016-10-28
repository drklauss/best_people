<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

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
     * @ORM\Column(name="avatar_link", type="string", length=255, nullable=true)
     */
    private $avatarLink;

    /**
     * @ORM\OneToMany(targetEntity="Messages", mappedBy="to_user_id")
     */

    private $toUserMessages;

    /**
     * @ORM\OneToMany(targetEntity="Votes", mappedBy="to_user_id")
     */

    private $toUserVotes;

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
     * Constructor
     */
    public function __construct()
    {
        $this->toUserMessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->toUserVotes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add toUserMessages
     *
     * @param \AppBundle\Entity\Messages $toUserMessages
     * @return Users
     */
    public function addToUserMessage(\AppBundle\Entity\Messages $toUserMessages)
    {
        $this->toUserMessages[] = $toUserMessages;

        return $this;
    }

    /**
     * Remove toUserMessages
     *
     * @param \AppBundle\Entity\Messages $toUserMessages
     */
    public function removeToUserMessage(\AppBundle\Entity\Messages $toUserMessages)
    {
        $this->toUserMessages->removeElement($toUserMessages);
    }

    /**
     * Get toUserMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getToUserMessages()
    {
        return $this->toUserMessages;
    }

    /**
     * Add toUserVotes
     *
     * @param \AppBundle\Entity\Votes $toUserVotes
     * @return Users
     */
    public function addToUserVote(\AppBundle\Entity\Votes $toUserVotes)
    {
        $this->toUserVotes[] = $toUserVotes;

        return $this;
    }

    /**
     * Remove toUserVotes
     *
     * @param \AppBundle\Entity\Votes $toUserVotes
     */
    public function removeToUserVote(\AppBundle\Entity\Votes $toUserVotes)
    {
        $this->toUserVotes->removeElement($toUserVotes);
    }

    /**
     * Get toUserVotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getToUserVotes()
    {
        return $this->toUserVotes;
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
}
