<?php

namespace App\Entity\Time\Application;

use App\Entity\Time\Domain\TimeEntryRepository;

class GetSummaryService
{
    private TimeEntryRepository $timeEntryRepository;

    public function __construct(TimeEntryRepository $timeEntryRepository)
    {
        $this->timeEntryRepository = $timeEntryRepository;
    }

    public function execute(): array
    {
        $results = $this->timeEntryRepository->getTodayTimeEntries();

        $totalDayDuration = array_sum(array_column($results, 'totalDuration'));

        return [
            'tasks' => $results,
            'totalDayDuration' => $totalDayDuration,
        ];
    }
}