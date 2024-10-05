<?php

namespace App\Entity\Task\Domain;

interface TaskRepository
{
    public function findByName(string $name): ?Task;
    public function save(Task $task): void;
}