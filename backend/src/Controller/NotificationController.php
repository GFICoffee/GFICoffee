<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class NotificationController extends AbstractController
{
  /** @var EntityManagerInterface */
  private $em;

  /** @var NotificationService */
  private $notificationService;

  /** @var JWTTokenManagerInterface */
  private $tokenManager;

  /** @var ParameterBagInterface */
  private $parameterBag;

  public function __construct(EntityManagerInterface $em,
                              NotificationService $notificationService,
                              JWTTokenManagerInterface $tokenManager,
                              ParameterBagInterface $parameterBag)
  {
    $this->em = $em;
    $this->notificationService = $notificationService;
    $this->tokenManager = $tokenManager;
    $this->parameterBag = $parameterBag;
  }

  /**
   * Envoie une notification aux utilisateurs concernés pour leur demander de payer/récupérer leur commande.
   *
   * @View()
   * @Post("/notification/pickup/{numfacture}/send")
   *
   * @param Request $request
   * @return mixed
   */
  public function sendPickupNotificationAction(Request $request, string $numfacture)
  {
    /** @var UserRepository $userRepo */
    $userRepo = $this->em->getRepository(User::class);
    $users = $userRepo->findUsersThatHaveToPay();
    $result = $this->notificationService->sendPickupNotification($users, $numfacture);
    return $result;
  }
}
