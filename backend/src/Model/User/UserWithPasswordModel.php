<?php

namespace App\Model\User;

use App\Entity\User;
use JMS\Serializer\Annotation\Type;

/**
 * Description de l'utilisateur, incluant son mot de passe.
 *
 * Class UserWithPasswordModel
 * @package App\Model\User
 */
class UserWithPasswordModel extends UserModel
{
    /**
     * @Type("string")
     *
     * @var string
     */
    private $password;

    /**
     * UserModel constructor.
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->password = $user->getPlainPassword();
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }


}