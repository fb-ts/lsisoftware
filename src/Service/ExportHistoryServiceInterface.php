<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Form\FormInterface;

interface ExportHistoryServiceInterface
{
    public function createForm(): FormInterface;

    public function getExportsHistory(FormInterface $form): array;
}
