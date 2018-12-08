<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Games
 * @Entity(repositoryClass="App\Repository\GamesRepository")
 * @ORM\Table(name="games", indexes={@ORM\Index(name="user_o", columns={"user_o"}), @ORM\Index(name="user_x", columns={"user_x"})})
 */
class Games
{
    const STATUS_PENDING_USER = 1;
    const STATUS_ACTIVE_GAME = 2;
    const STATUS_FINISHED_GAME = 3;

    const MOVE_X = 0;
    const MOVE_O = 1;

    public static $winCombinations = [3885,777,30,425,5980,4058,1681,696];
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
     * @ORM\Column(name="whose_move", type="boolean", nullable=true)
     */
    private $whoseMove;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
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

    /**
     * @var
     * @ORM\Column(name="user_x_count", type="integer", nullable=true)
     */
    private $userXCount;

    /**
     * @var
     * @ORM\Column(name="user_o_count", type="integer", nullable=true)
     */
    private $userOCount;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
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
