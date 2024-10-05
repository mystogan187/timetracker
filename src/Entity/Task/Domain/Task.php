<?php

namespace App\Entity\Task\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Time\Domain\TimeEntry;

#[ORM\Entity()]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(targetEntity: TimeEntry::class, mappedBy: 'task', cascade: ['persist', 'remove'])]
    private $timeEntries;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->timeEntries = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTimeEntries(): ArrayCollection
    {
        return $this->timeEntries;
    }

    public function setTimeEntries(ArrayCollection $timeEntries): void
    {
        $this->timeEntries = $timeEntries;
    }

}