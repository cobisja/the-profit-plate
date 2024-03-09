<?php

declare(strict_types=1);

namespace App\Tests\Service\Admin\Shared;

use App\Service\Admin\Shared\ActionButtonRendererService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ActionButtonRendererServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_renders_the_html_code_for_edit_and_delete_buttons(): void
    {
        $routes = ['edit_route', 'delete_route'];
        $entityId = '123';

        $loader = new FilesystemLoader(__DIR__ . '/../../../../templates');
        $twig = new Environment($loader);

        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);

        $csrfTokenManagerMock->expects($this->once())
            ->method('getToken')
            ->with('delete123')
            ->willReturn(new CsrfToken('delete' . $entityId, 'fake-csrf-token'));

        $actionButtonRendererService = new ActionButtonRendererService($twig, $csrfTokenManagerMock);

        $expectedEditButton = '<a href="edit_route"class="btn btn-sm btn-light-primary ms-lg-5 float-start"data-action="admin--shared--modal-form#openModal">Edit</a>';
        $expectedDeleteButton = '<form method="post"action="delete_route"data-controller="admin--shared--delete-item-button"data-action="admin--shared--delete-item-button#deleteItem"><input type="hidden" name="_token" value="fake-csrf-token"><input type="hidden" name="_method" value="delete"><button class="btn btn-sm btn-light-danger ms-lg-5">Delete</button></form>';

        /**
         * Due to the service uses "Heredoc" notation, the HTML code contains new line characters (PHP_EOL)
         * and unwanted extra spacing. So, the result is formatted in a way it can be compared with the
         * expected html for both buttons.
         */
        $result = preg_replace(
            '/\s{2,}/',
            '',
            str_replace(PHP_EOL, '', $actionButtonRendererService->execute($routes, $entityId))
        );

        $this->assertStringContainsString($expectedEditButton, $result);
        $this->assertStringContainsString($expectedDeleteButton, $result);
    }
}