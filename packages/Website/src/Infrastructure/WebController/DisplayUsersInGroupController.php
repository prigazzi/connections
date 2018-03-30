<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\WebController;

use Psr\Http\Message\ServerRequestInterface;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;
use Vonq\Website\Domain\Model\UserRepositoryInterface;
use Zend\Diactoros\Response\HtmlResponse;

class DisplayUsersInGroupController
{
    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var UserRepositoryInterface */
    private $userRepository;

    public function __construct(
        GroupRepositoryInterface $groupRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse('<H1>Hi</H1>');
    }
}
