<?php

namespace TwinElements\AdminBundle\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CrudLoggerMessage
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $routeName;

    private $logger;

    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $request, LoggerInterface $logger)
    {
        $this->routeName = $request->getCurrentRequest()->get('_route');
        $this->username = $tokenStorage->getToken()->getUsername();
        $this->logger = $logger;
    }

    public function createLog(int $id, string $title): void
    {
        $this->logger->info('User: ' . $this->username . '; Route: ' . $this->routeName . '; title: ' . $title . '; ID ' . $id);
    }
}
