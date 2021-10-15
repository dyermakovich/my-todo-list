<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private TaskRepository $taskRepository;
    private LoggerInterface $logger;

    public function __construct(TaskRepository $taskRepository, LoggerInterface $logger)
    {
        $this->taskRepository = $taskRepository;
        $this->logger = $logger;
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('base.html.twig' );
    }

    protected function createTaskNotFoundException($id): NotFoundHttpException {
        return $this->createNotFoundException('Task not found for id '.$id);
    }

    protected function throwTaskNotFoundExceptionIfNull(?Task $task, $id) {
        if($task == null) {
            throw $this->createTaskNotFoundException($id);
        }
    }

    #[Route('/api/tasks/move/{from<\d+>}/{to<\d+>}', name: 'task_move')]
    public function move($from, $to): Response
    {
        try {
            $taskFrom = $this->taskRepository->find($from);
            $taskTo = $this->taskRepository->find($to);

            $this->throwTaskNotFoundExceptionIfNull($taskFrom, $from);
            $this->throwTaskNotFoundExceptionIfNull($taskTo, $to);

            $aux = $taskFrom->getGravity();
            $taskFrom->setGravity($taskTo->getGravity());
            $taskTo->setGravity($aux);

            $this->getDoctrine()->getManager()->flush();

            return $this->json(true);
        } catch(\Exception $e) {
            throw $this->createNotFoundException('Can not change task order due tu exception', $e);
        }
    }
}
