<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\WebController;

use Psr\Http\Message\ServerRequestInterface;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;
use Vonq\Website\Domain\Model\UserRepositoryInterface;
use Vonq\Website\Infrastructure\Presentation\TemplateEngineInterface;
use Zend\Diactoros\Response\HtmlResponse;

class DisplayUsersInGroupController
{
    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var TemplateEngineInterface */
    private $templateEngine;

    public function __construct(
        GroupRepositoryInterface $groupRepository,
        UserRepositoryInterface $userRepository,
        TemplateEngineInterface $templateEngine
    )
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->templateEngine = $templateEngine;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $data = [
            'groupName' => 'Takodama'
        ];

        $view = $this->templateEngine->render('users_in_group.html', $data);

        return new HtmlResponse($view);
    }
}
