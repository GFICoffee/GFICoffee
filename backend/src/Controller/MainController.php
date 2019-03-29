<?php

namespace App\Controller;

use App\Entity\Coffee;
use App\Entity\User;
use App\Service\AuthService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;


class MainController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var AuthService */
    private $authService;

    /** @var NotificationService */
    private $notificationService;

    /** @var JWTTokenManagerInterface */
    private $tokenManager;

    /** @var ParameterBagInterface */
    private $parameterBag;

    public function __construct(EntityManagerInterface $em,
                                AuthService $authService,
                                NotificationService $notificationService,
                                JWTTokenManagerInterface $tokenManager,
                                ParameterBagInterface $parameterBag)
    {
      $this->em = $em;
      $this->authService = $authService;
      $this->notificationService = $notificationService;
      $this->tokenManager = $tokenManager;
      $this->parameterBag = $parameterBag;
    }

    /**
     * @View
     * @Route("/")
     */
    public function rootAction()
    {
        return new JsonResponse('It works');
    }

    /**
     * @View
     * @Get("/test")
     */
    public function testAction()
    {
      return "pong";
    }

    /**
     * Obtient la liste des cafés
     *
     * @View()
     * @Get("/coffee/list")
     *
     * @return Coffee[]
     */
    public function coffeeListAction()
    {
        $coffeeRepo = $this->em->getRepository(Coffee::class);

        return $coffeeRepo->findAll();
    }

    /**
     * @View()
     * @Get("/login")
     *
     * @return RedirectResponse
     */
    public function loginAction()
    {
        return new RedirectResponse($this->authService->getAuthorizationUrl());
    }

    /**
     * @View()
     * @Get("/authorize")
     *
     * @return string
     */
    public function authorizeAction(Request $request)
    {
        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+=-[]{};:"<>?,./';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        function getMailDomain(string $mail)
        {
            $explode = explode('@', $mail);
            return count($explode) === 2 ? $explode[1] : '';
        }

        $oauthCode = $request->get('code');
        $this->authService->setCode($oauthCode);
        $userInfo = $this->authService->getUserInfo();



        // Find user from sub, create if doesn't exists.
        $userRepo = $this->em->getRepository(User::class);
        /** @var UserInterface|null $user */
        $user = $userRepo->findOneBySub($userInfo['sub']);

        if (!array_key_exists('email', $userInfo) || getMailDomain($userInfo['email']) !== 'gfi.fr') {
            throw new \Exception('Vous devez utiliser une adresse email GFI pour vous connecter !');
        }

        if (!$user) {
            $user = new User();
            $user->setSub($userInfo['sub']);
            $user->setUsername(array_key_exists('email', $userInfo) ? $userInfo['email'] : '');
            $user->setEmail(array_key_exists('email', $userInfo) ? $userInfo['email'] : '');
            $user->setFirstname(array_key_exists('given_name', $userInfo) ? $userInfo['given_name'] : '');
            $user->setLastname(array_key_exists('family_name', $userInfo) ? $userInfo['family_name'] : '');
            $user->setRoles(array('ROLE_USER'));
            $user->setEnabled(true);
            $user->setPlainPassword(generateRandomString());
        }

        $user->setLastLogin(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
        $jwt = $this->tokenManager->create($user);

        $webappUrl = $this->parameterBag->get('webappUrl');
        return new RedirectResponse($webappUrl . "?token=" . $jwt);;
    }
}
