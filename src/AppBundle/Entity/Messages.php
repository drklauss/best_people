<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessagesRepository")
 */
class Messages
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
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

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
    private $messageFromUser;

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
     * @return Messages
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
     * @return Messages
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
     * Set body
     *
     * @param string $body
     * @return Messages
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Messages
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
     * Set messageFromUser
     *
     * @param \AppBundle\Entity\Users $messageFromUser
     * @return Messages
     */
    public function setMessageFromUser(\AppBundle\Entity\Users $messageFromUser = null)
    {
        $this->messageFromUser = $messageFromUser;

        return $this;
    }

    /**
     * Get messageFromUser
     *
     * @return \AppBundle\Entity\Users
     */
    public function getMessageFromUser()
    {
        return $this->messageFromUser;
    }
}
