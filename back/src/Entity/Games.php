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
     * @var Users|null
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_x", referencedColumnName="id")
     * })
     */
    private $userX;

    /**
     * @var Users|null
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_o", referencedColumnName="id")
     * })
     */
    private $userO;

    /**
     * @var
     * @ORM\Column(name="user_x_count", type="text", nullable=true)
     */
    private $userXCount;

    /**
     * @var
     * @ORM\Column(name="user_o_count", type="text", nullable=true)
     */
    private $userOCount;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_move", type="datetime", nullable=true)
     */
    private $lastMove;

    /**
     * @return \DateTime
     */
    public function getLastMove(): \DateTime
    {
        return $this->lastMove;
    }

    /**
     * @param \DateTime $lastMove
     * @return Games
     */
    public function setLastMove(\DateTime $lastMove): self
    {
        $this->lastMove = $lastMove;
        return $this;
    }


    /**
     * @return array|null
     */
    public function getUserOCount() :?array
    {
        return json_decode($this->userOCount, true);
    }

    /**
     * @param string $userOCount
     * @return Games
     */
    public function setUserOCount(string $userOCount) :self
    {
        $this->userOCount = json_encode($userOCount);
        return $this;
    }

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

    /**
     * @return array|null
     */
    public function getUserXCount() :?array
    {
        return json_decode($this->userXCount, true);
    }

    /**
     * @param string $userXCount
     * @return Games
     */
    public function setUserXCount(string $userXCount) :self
    {
        $this->userXCount = json_encode($userXCount);
        return $this;
    }

    /**
     * @param int $value
     * @return Games
     */
    public function updateUserOCount(int $value) :self
    {
        $count = json_decode($this->userOCount);
        if ($count){
            $count[] = $value;
        } else {
            $count = [$value];
        }
        $this->userOCount = json_encode($count);
        return $this;
    }
    
    /**
     * @param int $value
     * @return Games
     */
    public function updateUserXCount(int $value) :self
    {
        $count = json_decode($this->userXCount);
        if ($count){
            $count[] = $value;
        } else {
            $count = [$value];
        }
        $this->userXCount = json_encode($count);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasGameWinner() :bool 
    {
        return $this->isUserOWinner() || $this->isUserXWinner();
    }

    /**
     * @return Users|null
     */
    public function getWinner() :?Users
    {
        if ($this->status === self::STATUS_FINISHED_GAME){
            if ($this->whoseMove === self::MOVE_X){
                return $this->userX;
            } else if ($this->whoseMove === self::MOVE_O){
                return $this->userO;
            }
        } 
        return null;
    }

    private function isUserOWinner() :bool
    {
        return $this->isItWinCombination($this->getUserOCount());
    }

    private function isUserXWinner() :bool
    {
        return $this->isItWinCombination($this->getUserXCount());
    }

    private function isItWinCombination(array $a = null) :bool
    {
        if (empty($a)){
            return false;
        }
        return (
        (in_array(1,$a) && in_array(2,$a) && in_array(3,$a))
            ||
        (in_array(1,$a) && in_array(5,$a) && in_array(9,$a))
            ||
        (in_array(1,$a) && in_array(4,$a) && in_array(7,$a))
            ||
        (in_array(4,$a) && in_array(5,$a) && in_array(6,$a))
            ||
        (in_array(7,$a) && in_array(8,$a) && in_array(9,$a))
            ||
        (in_array(2,$a) && in_array(5,$a) && in_array(8,$a))
            ||
        (in_array(3,$a) && in_array(6,$a) && in_array(9,$a))
            ||
        (in_array(3,$a) && in_array(5,$a) && in_array(7,$a))
        );
    }
}
