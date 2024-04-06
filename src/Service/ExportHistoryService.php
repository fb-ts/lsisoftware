<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ExportHistoryRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

readonly class ExportHistoryService implements ExportHistoryServiceInterface
{
    public function __construct(
        private ExportHistoryRepositoryInterface $exportHistoryRepository,
        private FormFactoryInterface $formFactory,
        private string $formType
    ) {
    }

    public function createForm(): FormInterface
    {
        return $this->formFactory->create($this->formType);
    }

    public function getExportsHistory(FormInterface $form): array
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            return $this->exportHistoryRepository->findByFilters($formData);
        }

        return $this->exportHistoryRepository->findAll();
    }
}
