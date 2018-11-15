<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ordered_coffee")
 */
class OrderedCoffee
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     */
    protected $order;

    /**
     * @var Coffee
     * @ORM\ManyToOne(targetEntity="App\Entity\Coffee")
     */
    protected $coffee;

    /**
     * @var integer|null
     * @ORM\Column(nullable=true, type="integer")
     */
    protected $quantity30;

    /**
     * @var integer|null
     * @ORM\Column(nullable=true, type="integer")
     */
    protected $quantity50;

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

    /**
     * @return Coffee
     */
    public function getCoffee(): Coffee
    {
        return $this->coffee;
    }

    /**
     * @param Coffee $coffee
     */
    public function setCoffee(Coffee $coffee): void
    {
        $this->coffee = $coffee;
    }

    /**
     * @return int|null
     */
    public function getQuantity30(): ?int
    {
        return $this->quantity30;
    }

    /**
     * @param int|null $quantity30
     */
    public function setQuantity30(?int $quantity30): void
    {
        $this->quantity30 = $quantity30;
    }

    /**
     * @return int|null
     */
    public function getQuantity50(): ?int
    {
        return $this->quantity50;
    }

    /**
     * @param int|null $quantity50
     */
    public function setQuantity50(?int $quantity50): void
    {
        $this->quantity50 = $quantity50;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}