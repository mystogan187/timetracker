<?php

namespace App\Controller;

use App\Entity\Task\Application\StartTaskService;
use App\Entity\Time\Application\GetSummaryService;
use App\Entity\Time\Application\StopTaskService;
use App\Entity\Time\Domain\TimeEntryRepository;
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
    public function startTask(Request $request, StartTaskService $startTaskService): Response
    {
        $taskName = $request->request->get('task_name');

        try {
            $timeEntry = $startTaskService->execute($taskName);
            return $this->redirectToRoute('task_timer', ['id' => $timeEntry->getId()]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('index');
        }
    }

    #[Route('/timer/{id}', name: 'task_timer', methods: ['GET'])]
    public function showTimer(int $id, TimeEntryRepository $timeEntryRepository): Response
    {
        $timeEntry = $timeEntryRepository->findById($id);

        if (!$timeEntry || $timeEntry->getEndTime() !== null) {
            $this->addFlash('error', 'Esta tarea ya ha sido detenida.');
            return $this->redirectToRoute('index');
        }

        return $this->render('task/timer.html.twig', [
            'timeEntry' => $timeEntry,
        ]);
    }

    #[Route('/stop/{id}', name: 'task_stop', methods: ['POST'])]
    public function stopTask(int $id, StopTaskService $stopTaskService): Response
    {
        try {
            $stopTaskService->execute($id);
            return $this->redirectToRoute('task_summary');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('index');
        }
    }

    #[Route('/summary', name: 'task_summary', methods: ['GET'])]
    public function summary(GetSummaryService $getSummaryService): Response
    {
        $data = $getSummaryService->execute();

        return $this->render('task/summary.html.twig', [
            'tasks' => $data['tasks'],
            'totalDayDuration' => $data['totalDayDuration'],
        ]);
    }
}