<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Users
{
    const AVATARS_DIR = 'public/assets/images/avatars';

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
     * @Assert\Type("bool")
     */
    private $isFemale;

    /**
     * @var string
     * @ORM\Column(name="avatarLink", type="string", length=255, nullable=true)
     */
    private $avatarLink;

    /**
     * @ORM\OneToMany(targetEntity="Votes", mappedBy="toUser")
     */

    private $toUserVotes;

    /**
     * @ORM\OneToMany(targetEntity="Votes", mappedBy="fromUser")
     */

    private $fromUserVotes;

    /**
     * @ORM\OneToMany(targetEntity="Messages", mappedBy="toUser")
     */

    private $toUserMessages;

    /**
     * @ORM\OneToMany(targetEntity="Messages", mappedBy="fromUser")
     */

    private $fromUserMessages;

    /**
     * @Assert\Image(maxSize="1M")
     */
    private $image;

    /**
     * Temporally sets image path
     * @var $temp
     */
    private $temp;

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;
        // check if we have an old image path
        if (isset($this->avatarLink)) {
            // store the old name to delete after the update
            $this->temp = $this->avatarLink;
            $this->avatarLink = null;
        } else {
            $this->avatarLink = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->avatarLink = $filename.'.'.$this->getImage()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getImage()) {
            return;
        }

        $this->getImage()->move($this->getUploadRootDir(), $this->avatarLink);
        if (isset($this->temp)) {
            unlink($this->getUploadRootDir().'/'.$this->temp);
            $this->temp = null;
        }
        $this->image = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $this->avatarLink = null;
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Get Absolute FilePath
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->avatarLink
            ? null
            : $this->getUploadRootDir().'/'.$this->avatarLink;
    }

    /**
     * Get webPath, which can be used in templates
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->avatarLink
            ? null
            : self::AVATARS_DIR.'/'.$this->avatarLink;
    }

    protected function getUploadRootDir()
    {
        $avatarsDir = __DIR__.'/../../../web/'.self::AVATARS_DIR;
        $fs = new Filesystem();
        if(!$fs->exists($avatarsDir)){
            $fs->mkdir($avatarsDir);
        }
        return $avatarsDir;
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
     * Set avatarLink
     *
     * @param string $avatarLink
     *
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
     * Add toUserVote
     *
     * @param \AppBundle\Entity\Votes $toUserVote
     *
     * @return Users
     */
    public function addToUserVote(\AppBundle\Entity\Votes $toUserVote)
    {
        $this->toUserVotes[] = $toUserVote;

        return $this;
    }

    /**
     * Remove toUserVote
     *
     * @param \AppBundle\Entity\Votes $toUserVote
     */
    public function removeToUserVote(\AppBundle\Entity\Votes $toUserVote)
    {
        $this->toUserVotes->removeElement($toUserVote);
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
     * Add fromUserVote
     *
     * @param \AppBundle\Entity\Votes $fromUserVote
     *
     * @return Users
     */
    public function addFromUserVote(\AppBundle\Entity\Votes $fromUserVote)
    {
        $this->fromUserVotes[] = $fromUserVote;

        return $this;
    }

    /**
     * Remove fromUserVote
     *
     * @param \AppBundle\Entity\Votes $fromUserVote
     */
    public function removeFromUserVote(\AppBundle\Entity\Votes $fromUserVote)
    {
        $this->fromUserVotes->removeElement($fromUserVote);
    }

    /**
     * Get fromUserVotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFromUserVotes()
    {
        return $this->fromUserVotes;
    }

    /**
     * Add toUserMessage
     *
     * @param \AppBundle\Entity\Messages $toUserMessage
     *
     * @return Users
     */
    public function addToUserMessage(\AppBundle\Entity\Messages $toUserMessage)
    {
        $this->toUserMessages[] = $toUserMessage;

        return $this;
    }

    /**
     * Remove toUserMessage
     *
     * @param \AppBundle\Entity\Messages $toUserMessage
     */
    public function removeToUserMessage(\AppBundle\Entity\Messages $toUserMessage)
    {
        $this->toUserMessages->removeElement($toUserMessage);
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
     * Add fromUserMessage
     *
     * @param \AppBundle\Entity\Messages $fromUserMessage
     *
     * @return Users
     */
    public function addFromUserMessage(\AppBundle\Entity\Messages $fromUserMessage)
    {
        $this->fromUserMessages[] = $fromUserMessage;

        return $this;
    }

    /**
     * Remove fromUserMessage
     *
     * @param \AppBundle\Entity\Messages $fromUserMessage
     */
    public function removeFromUserMessage(\AppBundle\Entity\Messages $fromUserMessage)
    {
        $this->fromUserMessages->removeElement($fromUserMessage);
    }

    /**
     * Get fromUserMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFromUserMessages()
    {
        return $this->fromUserMessages;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->toUserVotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fromUserVotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->toUserMessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fromUserMessages = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
