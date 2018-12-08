<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Games
 *
 * @ORM\Table(name="games", indexes={@ORM\Index(name="user_o", columns={"user_o"}), @ORM\Index(name="user_x", columns={"user_x"})})
 * @ORM\Entity
 */
class Games
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="whose_move", type="boolean", nullable=false)
     */
    private $whoseMove;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_x", referencedColumnName="id")
     * })
     */
    private $userX;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_o", referencedColumnName="id")
     * })
     */
    private $userO;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhoseMove(): ?bool
    {
        return $this->whoseMove;
    }

    public function setWhoseMove(bool $whoseMove): self
    {
        $this->whoseMove = $whoseMove;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserX(): ?Users
    {
        return $this->userX;
    }

    public function setUserX(?Users $userX): self
    {
        $this->userX = $userX;

        return $this;
    }

    public function getUserO(): ?Users
    {
        return $this->userO;
    }

    public function setUserO(?Users $userO): self
    {
        $this->userO = $userO;

        return $this;
    }


}
