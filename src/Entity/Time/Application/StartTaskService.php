<?php

namespace App\Entity\Time\Application;


use App\Entity\Task\Domain\Task;
use App\Entity\Task\Domain\TaskRepository;
use App\Entity\Time\Domain\TimeEntry;
use App\Entity\Time\Domain\TimeEntryRepository;
use DateTime;
use DomainException;
use InvalidArgumentException;

class StartTaskService
{
    private TaskRepository $taskRepository;
    private TimeEntryRepository $timeEntryRepository;

    public function __construct(TaskRepository $taskRepository, TimeEntryRepository $timeEntryRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->timeEntryRepository = $timeEntryRepository;
    }

    public function execute(string $taskName): TimeEntry
    {
        if (empty($taskName)) {
            throw new InvalidArgumentException('Por favor, ingresa un nombre para la tarea.');
        }

        $task = $this->taskRepository->findByName($taskName);

        if (!$task) {
            $task = new Task($taskName);
            $this->taskRepository->save($task);
        }

        $existingTimeEntry = $this->timeEntryRepository->findActiveTimeEntry();

        if ($existingTimeEntry) {
            throw new DomainException('Ya hay una tarea en curso. Detén la tarea actual antes de iniciar una nueva.');
        }

        $timeEntry = new TimeEntry();
        $timeEntry->setTask($task);
        $timeEntry->setStartTime(new DateTime());

        $this->timeEntryRepository->save($timeEntry);

        return $timeEntry;
    }
}