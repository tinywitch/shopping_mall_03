<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;
/**
 * @ORM\Entity
 * @ORM\Table(name="activities")
 */
class Activity
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="activities")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    protected $sender;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getSender() 
    {
        return $this->sender;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setSender($sender) 
    {
        $this->sender = $sender;
        $sender->addActivity($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="activities")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    protected $receiver;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getReceiver() 
    {
        return $this->receiver;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setReceiver($receiver) 
    {
        $this->receiver = $receiver;
        $receiver->addNotification($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="type")
     */
    protected $type;

    /**
     * @ORM\Column(name="target_id")
     */
    protected $target_id;

    // Returns ID of this post.
    public function getId() 
    {
        return $this->id;
    }

    // Sets ID of this post.
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getType() 
    {
        return $this->type;
    }

    public function setType($type) 
    {
        $this->type = $type;
    }

    public function getTarget_id() 
    {
        return $this->target_id;
    }

    public function setTarget_id($target_id) 
    {
        $this->target_id = $target_id;
    }
}
