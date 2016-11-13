<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessagesRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="fromUserMessages")
     * @ORM\JoinColumn(name="fromUserId", referencedColumnName="id", nullable=false)
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="toUserMessages")
     * @ORM\JoinColumn(name="toUserId", referencedColumnName="id", nullable=false)
     */
    private $toUser;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * Set fromUser
     *
     * @param \AppBundle\Entity\Users $fromUser
     *
     * @return Messages
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
     * @return Messages
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
