<?php

namespace App\Entity\Time\Infrastructure;

use App\Entity\Time\Domain\TimeEntry;
use App\Entity\Time\Domain\TimeEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTimeEntryRepository implements TimeEntryRepository
{
    private EntityManagerInterface $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(TimeEntry::class);
    }

    public function findActiveTimeEntry(): ?TimeEntry
    {
        return $this->repository->findOneBy(['endTime' => null]);
    }

    public function findById(int $id): ?TimeEntry
    {
        return $this->repository->find($id);
    }

    public function save(TimeEntry $timeEntry): void
    {
        $this->entityManager->persist($timeEntry);
        $this->entityManager->flush();
    }

    public function getTodayTimeEntries(): array
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t.name as taskName, SUM(te.duration) as totalDuration')
            ->from(TimeEntry::class, 'te')
            ->join('te.task', 't')
            ->where('te.startTime >= :today')
            ->setParameter('today', $today)
            ->groupBy('t.id');

        return $qb->getQuery()->getResult();
    }
}