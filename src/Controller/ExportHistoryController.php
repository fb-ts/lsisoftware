<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExportHistoryServiceInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExportHistoryController extends AbstractController
{

    public function __construct(private readonly ExportHistoryServiceInterface $exportHistoryService)
    {
    }

    #[Route('/export-history', name: 'export_history_list')]
    #[Template('export-history/index.html.twig')]
    public function index(Request $request): array
    {
        $form = $this->exportHistoryService->createForm();
        $form->handleRequest($request);

        $exportsHistory = $this->exportHistoryService->getExportsHistory($form);

        return [
            'form' => $form->createView(),
            'exportsHistory' => $exportsHistory,
        ];
    }
}
