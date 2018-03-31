<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\WebController;

use Psr\Http\Message\ServerRequestInterface;
use Vonq\Website\Application\Service\UserGroupService;
use Vonq\Website\Domain\Model\GroupId;
use Vonq\Website\Infrastructure\Presentation\TemplateEngineInterface;
use Zend\Diactoros\Response\HtmlResponse;

class DisplayUsersInGroupController
{
    /** @var TemplateEngineInterface */
    private $templateEngine;

    /** @var UserGroupService */
    private $userGroupService;

    public function __construct(
        UserGroupService $userGroupService,
        TemplateEngineInterface $templateEngine
    ) {
        $this->templateEngine = $templateEngine;
        $this->userGroupService = $userGroupService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $groupListViewData = $this->userGroupService->userListInformationForGroup(
            GroupId::fromString(UserGroupService::DEFAULT_GROUP_ID)
        );

        $view = $this->templateEngine->render('users_in_group.html', $groupListViewData);

        return new HtmlResponse($view);
    }
}
