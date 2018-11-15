<?php
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="coffee_orders")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    protected $user;


    /**
     * @var Collection|OrderedCoffee[]|null
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedCoffee", mappedBy="order", cascade={"all"})
     */
    protected $items;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isWaiting = true;

    /**
     * @var \DateTime|null
     * @ORM\Column(nullable=true, type="datetime")
     */
    protected $validationDate;

    /**
     * @return \DateTime|null
     */
    public function getValidationDate(): ?\DateTime
    {
        return $this->validationDate;
    }

    /**
     * @param \DateTime|null $validationDate
     */
    public function setValidationDate(?\DateTime $validationDate): void
    {
        $this->validationDate = $validationDate;
    }

    /**
     * @return bool
     */
    public function isWaiting(): bool
    {
        return $this->isWaiting;
    }

    /**
     * @param bool $isWaiting
     */
    public function setIsWaiting(bool $isWaiting): void
    {
        $this->isWaiting = $isWaiting;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return OrderedCoffee[]|Collection|null
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderedCoffee[]|Collection|null $items
     */
    public function setItems($items): void
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
}