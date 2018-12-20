<?php
namespace App\Model\Coffee;

use App\Entity\User;
use App\Model\User\UserModel;
use JMS\Serializer\Annotation as Serializer;

class OrderDto
{
    /**
     * @var integer|null
     * @Serializer\Type("integer")
     */
    protected $id;

    /**
     * @var UserModel|null
     * @Serializer\Type("App\Model\User\UserModel")
     */
    protected $user;

    /**
     * @var CoffeeModel[]
     * @Serializer\Type("array<App\Model\Coffee\CoffeeModel>")
     */
    protected $items;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    protected $paid;

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid(bool $paid): void
    {
        $this->paid = $paid;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return UserModel|null
     */
    public function getUser(): ?UserModel
    {
        return $this->user;
    }

    /**
     * @param UserModel|null $user
     */
    public function setUser(?UserModel $user): void
    {
        $this->user = $user;
    }

    /**
     * @return CoffeeModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param CoffeeModel[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

}