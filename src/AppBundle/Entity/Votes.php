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
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="id")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     */
    private $fromUserId;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="id")
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fromUserId
     *
     * @param integer $fromUserId
     * @return Votes
     */
    public function setFromUserId($fromUserId)
    {
        $this->fromUserId = $fromUserId;

        return $this;
    }

    /**
     * Get fromUserId
     *
     * @return integer
     */
    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    /**
     * Set toUserId
     *
     * @param integer $toUserId
     * @return Votes
     */
    public function setToUserId($toUserId)
    {
        $this->toUserId = $toUserId;

        return $this;
    }

    /**
     * Get toUserId
     *
     * @return integer
     */
    public function getToUserId()
    {
        return $this->toUserId;
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
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     * @return Votes
     */
    public function setUser(\AppBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
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
