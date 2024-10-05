<?php

namespace App\Entity\Time\Domain;

interface TimeEntryRepository
{
    public function findActiveTimeEntry(): ?TimeEntry;
    public function findById(int $id): ?TimeEntry;
    public function save(TimeEntry $timeEntry): void;
    public function getTodayTimeEntries(): array;
}