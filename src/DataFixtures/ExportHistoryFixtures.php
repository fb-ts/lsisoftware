<?php

namespace App\DataFixtures;

use App\Entity\ExportHistory;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class ExportHistoryFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(ExportHistory::class, 10, function (ExportHistory $exportHistory) {
            $this->createExportHistory($exportHistory);
        });
    }

    protected function createExportHistory(ExportHistory $exportHistory): void
    {
        $exportHistory->setName($this->faker->sentence(3));
        $exportHistory->setUserName($this->faker->userName);
        $exportHistory->setLocationName($this->faker->city);
        $exportHistory->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year')));
    }
}
