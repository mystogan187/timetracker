<?php

namespace App\Entity\Task\Infrastructure;

use App\Entity\Task\Domain\Task;
use App\Entity\Task\Domain\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTaskRepository implements TaskRepository
{
    private EntityManagerInterface $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Task::class);
    }

    public function findByName(string $name): ?Task
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function save(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}