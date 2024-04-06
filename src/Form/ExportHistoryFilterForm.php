<?php

declare(strict_types=1);

namespace App\Form;

use App\Repository\ExportHistoryRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ExportHistoryFilterForm extends AbstractType
{
    public function __construct(private readonly ExportHistoryRepositoryInterface $exportHistoryRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locations = $this->exportHistoryRepository->findUniqueLocations();

        $builder
            ->add('startDate', ...$this->createDateType('Od:'))
            ->add('endDate', ...$this->createDateType('Do:'))
            ->add('location', ...$this->createChoiceType(array_combine($locations, $locations), 'Lokal:'))
            ->add('submit', ...$this->createSubmitType('Zatwierdź'));
    }

    private function createDateType(string $placeholder): array
    {
        return [
            DateType::class,
            [
                'label' => false,
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['autocomplete' => 'off', 'placeholder' => $placeholder, 'class' => 'form-control'],
                'required' => false,
            ]
        ];
    }

    private function createChoiceType(array $choices, string $placeholder): array
    {
        return [
            ChoiceType::class,
            [
                'label' => false,
                'choices' => $choices,
                'placeholder' => $placeholder,
                'attr' => ['class' => 'form-select'],
                'required' => false,
            ]
        ];
    }

    private function createSubmitType(string $label): array
    {
        return [
            SubmitType::class,
            ['label' => $label, 'attr' => ['class' => 'btn btn-primary']]
        ];
    }
}
