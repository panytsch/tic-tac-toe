<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Users
 *
 * @Entity(repositoryClass="App\Repository\UsersRepository")
 * @ORM\Table(name="users")
 */
class Users
{
    const STATUS_LEAVED = 0;
    const STATUS_READY = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="is_active", type="smallint", nullable=false)
     */
    private $isActive;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Users
     */
    public function setId(int $id): Users
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Users
     */
    public function setName(string $name): Users
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getisActive(): int
    {
        return $this->isActive;
    }

    /**
     * @param int $isActive
     * @return Users
     */
    public function setIsActive(int $isActive): Users
    {
        $this->isActive = $isActive;
        return $this;
    }

}
