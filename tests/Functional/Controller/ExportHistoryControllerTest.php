<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\ExportHistory;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExportHistoryControllerTest extends WebTestCase
{
    private EntityManager $entityManager;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    private function createExportHistory(
        int $id,
        string $name,
        string $userName,
        string $locationName,
        ?string $createdAt = null
    ): void {
        $exportHistory = new ExportHistory();
        $exportHistory->setId($id);
        $exportHistory->setName($name);
        $exportHistory->setUserName($userName);
        $exportHistory->setLocationName($locationName);
        if ($createdAt !== null) {
            $exportHistory->setCreatedAt(new DateTimeImmutable($createdAt));
        }
        $this->entityManager->persist($exportHistory);
    }

    public static function indexResponseIsSuccessfulProvider(): array
    {
        return [
            'empty' => ['exportHistory' => [], 'expected' => 'Brak wyników'],
            '1 result' => ['exportHistory' => [1, 'Export 1', 'User 1', 'Location 1'], 'expected' => 'Export 1'],
        ];
    }

    #[DataProvider('indexResponseIsSuccessfulProvider')]
    public function testIndexResponseIsSuccessful(array $exportHistory, string $expected): void
    {
        if (!empty($exportHistory)) {
            $this->createExportHistory(...$exportHistory);
            $this->entityManager->flush();
        }

        $this->client->request('GET', '/export-history');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Raport');
        self::assertAnySelectorTextSame('table td', $expected);
    }


    public static function indexFilterByProvider(): array
    {
        return [
            'startDate' => [
                'filter' => ['startDate' => '2024-04-01'],
                'count' => 2,
                'expected' => ['Export 2', 'Export 2'],
                'notExpected' => ['Export 1']
            ],
            'endDate' => [
                'filter' => ['endDate' => '2024-03-15'],
                'count' => 1,
                'expected' => ['Export 1'],
                'notExpected' => ['Export 2', 'Export 3']
            ],
            'location' => [
                'filter' => ['location' => 'Location 1'],
                'count' => 1,
                'expected' => ['Export 1'],
                'notExpected' => ['Export 2', 'Export 3']
            ],
            'startDate endDate' => [
                'filter' => ['startDate' => '2024-03-01', 'endDate' => '2024-04-01'],
                'count' => 1,
                'expected' => ['Export 1'],
                'notExpected' => ['Export 2', 'Export 3']
            ],
            'startDate endDate location' => [
                'filter' => ['startDate' => '2024-03-01', 'endDate' => '2024-04-01', 'location' => 'Location 2'],
                'count' => 1,
                'expected' => ['Brak wyników'],
                'notExpected' => ['Export 1', 'Export 2', 'Export 3']
            ],
            'empty' => [
                'filter' => [],
                'count' => 3,
                'expected' => ['Export 1', 'Export 2', 'Export 3'],
                'notExpected' => []
            ],
        ];
    }


    #[DataProvider('indexFilterByProvider')]
    public function testIndexFilterBy(
        array $filter,
        int $count,
        array $expected,
        array $notExpected
    ): void {
        $this->createExportHistory(1, 'Export 1', 'User 1', 'Location 1', '2024-03-01 12:31:21');
        $this->createExportHistory(2, 'Export 2', 'User 2', 'Location 2', '2024-04-01 11:47:00');
        $this->createExportHistory(3, 'Export 3', 'User 3', 'Location 3', '2024-05-11 00:00:00');
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', '/export-history');

        $form = $crawler->selectButton('Zatwierdź')->form();
        $formName = $form->getName();

        $values = [];

        foreach ($filter as $field => $value) {
            $values["{$formName}[$field]"] = $value;
        }
        $this->client->submit($form, $values);

        self::assertResponseIsSuccessful();
        self::assertSelectorCount($count, 'table tbody tr');
        foreach ($expected as $text) {
            self::assertAnySelectorTextContains('table td', $text);
        }
        foreach ($notExpected as $text) {
            self::assertAnySelectorTextNotContains('table td', $text);
        }
    }
}
