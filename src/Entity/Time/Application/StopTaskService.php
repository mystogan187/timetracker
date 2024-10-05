<?php

namespace App\Entity\Time\Application;

use App\Entity\Time\Domain\TimeEntryRepository;
use DateTime;
use DomainException;
use InvalidArgumentException;

class StopTaskService
{
    private TimeEntryRepository $timeEntryRepository;

    public function __construct(TimeEntryRepository $timeEntryRepository)
    {
        $this->timeEntryRepository = $timeEntryRepository;
    }

    public function execute(int $timeEntryId): void
    {
        $timeEntry = $this->timeEntryRepository->findById($timeEntryId);

        if (!$timeEntry) {
            throw new InvalidArgumentException('Esta entrada de tiempo no existe.');
        }

        if ($timeEntry->getEndTime() !== null) {
            throw new DomainException('Esta tarea ya ha sido detenida.');
        }

        $timeEntry->setEndTime(new DateTime());
        $duration = $timeEntry->getEndTime()->getTimestamp() - $timeEntry->getStartTime()->getTimestamp();
        $timeEntry->setDuration($duration);

        $this->timeEntryRepository->save($timeEntry);
    }
}