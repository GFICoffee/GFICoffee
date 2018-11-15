<?php
namespace App\Model\Coffee;

use JMS\Serializer\Annotation as Serializer;

class CoffeeModel extends \App\Entity\Coffee
{
    /**
     * @var integer|null
     * @Serializer\Type("integer")
     */
    protected $quantity30;

    /**
     * @var integer|null
     * @Serializer\Type("integer")
     */
    protected $quantity50;

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
}