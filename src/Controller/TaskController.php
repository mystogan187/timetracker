<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TimeEntry;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('task/index.html.twig');
    }

    #[Route('/start', name: 'task_start', methods: ['POST'])]
    public function startTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taskName = $request->request->get('task_name');

        if (empty($taskName)) {
            $this->addFlash('error', 'Por favor, ingresa un nombre para la tarea.');
            return $this->redirectToRoute('index');
        }

        $task = $entityManager->getRepository(Task::class)->findOneBy(['name' => $taskName]);

        if (!$task) {
            $task = new Task();
            $task->setName($taskName);

            $entityManager->persist($task);
            $entityManager->flush();
        }

        $existingTimeEntry = $entityManager->getRepository(TimeEntry::class)->findOneBy([
            'endTime' => null,
        ]);

        if ($existingTimeEntry) {
            $this->addFlash('error', 'Ya hay una tarea en curso. Detén la tarea actual antes de iniciar una nueva.');
            return $this->redirectToRoute('index');
        }

        $timeEntry = new TimeEntry();
        $timeEntry->setTask($task);
        $timeEntry->setStartTime(new DateTime());

        $entityManager->persist($timeEntry);
        $entityManager->flush();

        return $this->redirectToRoute('task_timer', ['id' => $timeEntry->getId()]);
    }

    #[Route('/timer/{id}', name: 'task_timer', methods: ['GET'])]
    public function showTimer(TimeEntry $timeEntry): Response
    {
        if ($timeEntry->getEndTime() !== null) {
            $this->addFlash('error', 'Esta tarea ya ha sido detenida.');
            return $this->redirectToRoute('index');
        }

        return $this->render('task/timer.html.twig', [
            'timeEntry' => $timeEntry,
        ]);
    }

    #[Route('/stop/{id}', name: 'task_stop', methods: ['POST'])]
    public function stopTask(TimeEntry $timeEntry, EntityManagerInterface $entityManager): Response
    {
        if ($timeEntry->getEndTime() !== null) {
            $this->addFlash('error', 'Esta tarea ya ha sido detenida.');
            return $this->redirectToRoute('index');
        }

        $timeEntry->setEndTime(new DateTime());

        $duration = $timeEntry->getEndTime()->getTimestamp() - $timeEntry->getStartTime()->getTimestamp();
        $timeEntry->setDuration($duration);

        $entityManager->flush();

        return $this->redirectToRoute('task_summary');
    }

    #[Route('/summary', name: 'task_summary', methods: ['GET'])]
    public function summary(EntityManagerInterface $entityManager): Response
    {
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        $qb = $entityManager->createQueryBuilder();
        $qb->select('t.name as taskName, SUM(te.duration) as totalDuration')
            ->from(TimeEntry::class, 'te')
            ->join('te.task', 't')
            ->where('te.startTime >= :today')
            ->setParameter('today', $today)
            ->groupBy('t.id');

        $results = $qb->getQuery()->getResult();

        $totalDayDuration = array_sum(array_column($results, 'totalDuration'));

        return $this->render('task/summary.html.twig', [
            'tasks' => $results,
            'totalDayDuration' => $totalDayDuration,
        ]);
    }
}