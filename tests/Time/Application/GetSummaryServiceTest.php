<?php

namespace App\Tests\Time\Application;


use App\Entity\Time\Application\GetSummaryService;
use App\Entity\Time\Domain\TimeEntryRepository;
use PHPUnit\Framework\TestCase;

class GetSummaryServiceTest extends TestCase
{
    private $timeEntryRepository;
    private $getSummaryService;

    protected function setUp(): void
    {
        $this->timeEntryRepository = $this->createMock(TimeEntryRepository::class);
        $this->getSummaryService = new GetSummaryService($this->timeEntryRepository);
    }

    public function testGetSummarySuccessfully()
    {
        $mockResults = [
            ['taskName' => 'Task 1', 'totalDuration' => 3600],
            ['taskName' => 'Task 2', 'totalDuration' => 1800],
        ];

        $this->timeEntryRepository->method('getTodayTimeEntries')
            ->willReturn($mockResults);

        $data = $this->getSummaryService->execute();

        $this->assertArrayHasKey('tasks', $data);
        $this->assertArrayHasKey('totalDayDuration', $data);
        $this->assertEquals(5400, $data['totalDayDuration']);
        $this->assertCount(2, $data['tasks']);
    }

    public function testGetSummaryWhenNoEntries()
    {
        $this->timeEntryRepository->method('getTodayTimeEntries')
            ->willReturn([]);

        $data = $this->getSummaryService->execute();

        $this->assertArrayHasKey('tasks', $data);
        $this->assertArrayHasKey('totalDayDuration', $data);
        $this->assertEquals(0, $data['totalDayDuration']);
        $this->assertCount(0, $data['tasks']);
    }
}