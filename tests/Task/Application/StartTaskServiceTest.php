<?php

namespace App\Tests\Task\Application;


use App\Entity\Task\Application\StartTaskService;
use App\Entity\Task\Domain\Task;
use App\Entity\Task\Domain\TaskRepository;
use App\Entity\Time\Domain\TimeEntry;
use App\Entity\Time\Domain\TimeEntryRepository;
use PHPUnit\Framework\TestCase;

class StartTaskServiceTest extends TestCase
{
    private $taskRepository;
    private $timeEntryRepository;
    private $startTaskService;

    protected function setUp(): void
    {
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->timeEntryRepository = $this->createMock(TimeEntryRepository::class);
        $this->startTaskService = new StartTaskService(
            $this->taskRepository,
            $this->timeEntryRepository
        );
    }

    public function testStartTaskSuccessfullyWhenTaskDoesNotExist()
    {
        $taskName = 'New Task';

        $this->taskRepository->method('findByName')
            ->with($taskName)
            ->willReturn(null);

        $this->timeEntryRepository->method('findActiveTimeEntry')
            ->willReturn(null);

        $this->taskRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Task::class));

        $this->timeEntryRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(TimeEntry::class));

        $timeEntry = $this->startTaskService->execute($taskName);

        $this->assertInstanceOf(TimeEntry::class, $timeEntry);
        $this->assertEquals($taskName, $timeEntry->getTask()->getName());
        $this->assertNotNull($timeEntry->getStartTime());
    }

    public function testStartTaskSuccessfullyWhenTaskExists()
    {
        $taskName = 'Existing Task';
        $existingTask = new Task($taskName);

        $this->taskRepository->method('findByName')
            ->with($taskName)
            ->willReturn($existingTask);

        $this->timeEntryRepository->method('findActiveTimeEntry')
            ->willReturn(null);

        $this->taskRepository->expects($this->never())
            ->method('save');

        $this->timeEntryRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(TimeEntry::class));

        $timeEntry = $this->startTaskService->execute($taskName);

        $this->assertInstanceOf(TimeEntry::class, $timeEntry);
        $this->assertSame($existingTask, $timeEntry->getTask());
        $this->assertNotNull($timeEntry->getStartTime());
    }

    public function testStartTaskWhenActiveTimeEntryExists()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Ya hay una tarea en curso. Detén la tarea actual antes de iniciar una nueva.');

        $taskName = 'New Task';

        $this->taskRepository->method('findByName')
            ->with($taskName)
            ->willReturn(null);

        $activeTimeEntry = $this->createMock(TimeEntry::class);
        $this->timeEntryRepository->method('findActiveTimeEntry')
            ->willReturn($activeTimeEntry);

        $this->startTaskService->execute($taskName);
    }

    public function testStartTaskWithEmptyTaskName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Por favor, ingresa un nombre para la tarea.');

        $taskName = '';

        $this->startTaskService->execute($taskName);
    }
}