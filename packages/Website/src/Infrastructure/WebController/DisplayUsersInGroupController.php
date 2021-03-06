<?php
declare(strict_types=1);

namespace Connections\Website\Infrastructure\WebController;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Connections\Website\Application\Service\UserGroupService;
use Connections\Website\Domain\Model\GroupId;
use Connections\Website\Infrastructure\Presentation\TemplateEngineInterface;
use Zend\Diactoros\Response\EmptyResponse;
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
        try {
            $groupListViewData = $this->userGroupService->userListInformationForGroup(
                GroupId::fromString(UserGroupService::DEFAULT_GROUP_ID)
            );
            $view = $this->templateEngine->render('users_in_group.html', $groupListViewData);
            $response = new HtmlResponse($view);
        } catch (InvalidArgumentException $exception) {
            $response = (new EmptyResponse())->withStatus(404);
        }

        return $response;
    }
}
