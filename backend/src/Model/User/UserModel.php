<?php

namespace App\Model\User;

use App\Entity\User;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description de l'utilisateur.
 *
 * Class UserModel
 * @package App\Model\User
 */
class UserModel implements UserInterface
{
    /**
     * @Type("integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $username;

    /**
     * @Type("string")
     *
     * @var string
     */
    private $email;

    /**
     * @Type("boolean")
     *
     * @var  bool
     */
    private $enabled;

    /**
     * @Type("boolean")
     *
     * @var bool
     */
    private $superAdmin;

    /**
     * @Type("array<string>")
     *
     * @var string[]
     */
    private $roles;

    /**
     * UserModel constructor.
     */
    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->username = $user->getUsername();
        $this->email = $user->getEmail();
        $this->enabled = $user->isEnabled();
        $this->superAdmin = $user->isSuperAdmin();
        $this->roles = $user->getRoles();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->superAdmin;
    }

    /**
     * @param bool $superAdmin
     */
    public function setSuperAdmin(bool $superAdmin)
    {
        $this->superAdmin = $superAdmin;
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}