<?php

namespace App\Tests\Time\Application;


use App\Entity\Time\Application\StopTaskService;
use App\Entity\Time\Domain\TimeEntry;
use App\Entity\Time\Domain\TimeEntryRepository;
use PHPUnit\Framework\TestCase;

class StopTaskServiceTest extends TestCase
{
    private $timeEntryRepository;
    private $stopTaskService;

    protected function setUp(): void
    {
        $this->timeEntryRepository = $this->createMock(TimeEntryRepository::class);
        $this->stopTaskService = new StopTaskService($this->timeEntryRepository);
    }

    public function testStopTimeEntrySuccessfully()
    {
        $timeEntryId = 1;
        $timeEntry = new TimeEntry();
        $timeEntry->setStartTime(new \DateTime('-1 hour'));

        $this->timeEntryRepository->method('findById')
            ->with($timeEntryId)
            ->willReturn($timeEntry);

        $this->timeEntryRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(TimeEntry::class));

        $this->stopTaskService->execute($timeEntryId);

        $this->assertNotNull($timeEntry->getEndTime());
        $this->assertGreaterThan(0, $timeEntry->getDuration());
    }

    public function testStopTimeEntryThatDoesNotExist()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Esta entrada de tiempo no existe.');

        $timeEntryId = 999;

        $this->timeEntryRepository->method('findById')
            ->with($timeEntryId)
            ->willReturn(null);

        $this->stopTaskService->execute($timeEntryId);
    }

    public function testStopTimeEntryAlreadyStopped()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Esta tarea ya ha sido detenida.');

        $timeEntryId = 1;
        $timeEntry = new TimeEntry();
        $timeEntry->setStartTime(new \DateTime('-2 hours'));
        $timeEntry->setEndTime(new \DateTime('-1 hour'));

        $this->timeEntryRepository->method('findById')
            ->with($timeEntryId)
            ->willReturn($timeEntry);

        $this->stopTaskService->execute($timeEntryId);
    }
}