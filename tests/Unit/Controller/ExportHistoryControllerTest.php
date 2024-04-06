<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\ExportHistoryController;
use App\Service\ExportHistoryServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

class ExportHistoryControllerTest extends TestCase
{
    private function createMockForm(bool $isValid): FormInterface
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $form->method('isValid')->willReturn($isValid);

        $formView = $this->createMock(FormView::class);
        $form->method('createView')->willReturn($formView);

        return $form;
    }

    public static function formValidityProvider(): array
    {
        return [
            'Valid form' => [true],
            'Invalid form' => [false],
        ];
    }

    #[DataProvider('formValidityProvider')]
    public function testIndexReturnsExpectedData(bool $isValid): void
    {
        $form = $this->createMockForm($isValid);

        $exportHistoryService = $this->createMock(ExportHistoryServiceInterface::class);
        $exportHistoryController = new ExportHistoryController($exportHistoryService);

        $exportHistoryService->method('createForm')->willReturn($form);
        $exportHistoryService->method('getExportsHistory')->willReturn([]);

        $request = new Request();

        $result = $exportHistoryController->index($request);

        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('exportsHistory', $result);
    }
}
