<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Votes
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VotesRepository")
 */
class Votes
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="votes")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    private $fromUserId;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="votes")
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     */
    private $toUserId;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_good_vote", type="boolean")
     */
    private $isGoodVote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="Users", inversedBy="id")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    private $voteFromUser;
    

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
     * Set fromUserId
     *
     * @param \AppBundle\Entity\Users $fromUserId
     * @return Votes
     */
    public function setFromUserId(\AppBundle\Entity\Users $fromUserId)
    {
        $this->fromUserId = $fromUserId;

        return $this;
    }

    /**
     * Get fromUserId
     *
     * @return \AppBundle\Entity\Users 
     */
    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    /**
     * Set toUserId
     *
     * @param \AppBundle\Entity\Users $toUserId
     * @return Votes
     */
    public function setToUserId(\AppBundle\Entity\Users $toUserId)
    {
        $this->toUserId = $toUserId;

        return $this;
    }

    /**
     * Get toUserId
     *
     * @return \AppBundle\Entity\Users 
     */
    public function getToUserId()
    {
        return $this->toUserId;
    }

    /**
     * Set voteFromUser
     *
     * @param \AppBundle\Entity\Users $voteFromUser
     * @return Votes
     */
    public function setVoteFromUser(\AppBundle\Entity\Users $voteFromUser = null)
    {
        $this->voteFromUser = $voteFromUser;

        return $this;
    }

    /**
     * Get voteFromUser
     *
     * @return \AppBundle\Entity\Users 
     */
    public function getVoteFromUser()
    {
        return $this->voteFromUser;
    }
}
