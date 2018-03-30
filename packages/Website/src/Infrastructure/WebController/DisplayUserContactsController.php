<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\WebController;

use Psr\Http\Message\ServerRequestInterface;
use Vonq\Website\Application\Service\UserGroupService;
use Vonq\Website\Domain\Model\UserId;
use Vonq\Website\Infrastructure\Presentation\TemplateEngineInterface;
use Zend\Diactoros\Response\HtmlResponse;

class DisplayUserContactsController
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
        $userContactsViewData = $this->userGroupService->contactsForUser(
            UserId::fromString($request->getAttribute('userId'))
        );

        $view = $this->templateEngine->render('user_contacts.html', $userContactsViewData);

        return new HtmlResponse($view);
    }
}
