<?php

namespace Pantono\Forms\Actions;

use Pantono\Queue\QueueManager;
use Pantono\Forms\Model\FormSubmission;

class CreateQueueTaskActionController extends AbstractActionController
{
    private QueueManager $queueManager;

    public function __construct(QueueManager $queueManager)
    {
        $this->queueManager = $queueManager;
    }

    public function execute(FormSubmission $formSubmission, array $config = []): string
    {
        $taskName = $config['task_name'] ?? null;
        $additionalContext = $config['additional_context'] ?? null;
        if ($additionalContext !== null) {
            $additionalContext = json_decode($additionalContext, true);
        } else {
            $additionalContext = [];
        }
        $additionalContext['submission'] = $formSubmission->getFieldArray();
        $tasks = $this->queueManager->createTask($taskName, $additionalContext);
        if (empty($tasks)) {
            throw new \RuntimeException('No tasks were created');
        }
        return (string)$tasks[0]->getId();
    }


}
