<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Votes
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VotesRepository")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Votes
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="fromUserVotes")
     * @ORM\JoinColumn(name="fromUserId", referencedColumnName="id")
     */
    private $fromUser;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="toUserVotes")
     * @ORM\JoinColumn(name="toUserId", referencedColumnName="id")
     */
    private $toUser;

    /**
     * @var bool
     *
     * @ORM\Column(name="isGoodVote", type="boolean")
     */
    private $isGoodVote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setDateTimeNow()
    {
        if ($this->getDate() == null) {
            $this->date = new \DateTime('now');
        }
    }

    /**
     * Set isGoodVote
     *
     * @param boolean $isGoodVote
     * @return Votes
     */
    public function setIsGoodVote($isGoodVote)
    {
        $this->isGoodVote = $isGoodVote;

        return $this;
    }

    /**
     * Get isGoodVote
     *
     * @return boolean
     */
    public function getIsGoodVote()
    {
        return $this->isGoodVote;
    }

 
    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Votes
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set fromUser
     *
     * @param \AppBundle\Entity\Users $fromUser
     *
     * @return Votes
     */
    public function setFromUser(\AppBundle\Entity\Users $fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return \AppBundle\Entity\Users
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param \AppBundle\Entity\Users $toUser
     *
     * @return Votes
     */
    public function setToUser(\AppBundle\Entity\Users $toUser)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \AppBundle\Entity\Users
     */
    public function getToUser()
    {
        return $this->toUser;
    }
}
