<?php

namespace Pantono\Forms\Actions;

use Pantono\Email\Email;
use Pantono\Forms\Model\FormSubmission;
use Pantono\Email\EmailTemplates;

class SendEmailActionController extends AbstractActionController
{
    private Email $email;
    private EmailTemplates $emailTemplates;

    public function __construct(Email $email, EmailTemplates $emailTemplates)
    {
        $this->email = $email;
        $this->emailTemplates = $emailTemplates;
    }

    public function execute(FormSubmission $formSubmission, array $config = []): string
    {
        $templateId = $config['template_id'] ?? null;
        $toAddress = $config['to_address'] ?? null;
        $subject = $config['subject'] ?? null;

        $template = $this->emailTemplates->getTemplateById($templateId);
        if (!$template) {
            throw new \RuntimeException(sprintf('Email template %s not found', $templateId));
        }

        $templateHtml = $this->emailTemplates->renderTemplate($template, [
            'submission' => $formSubmission->getFieldArray(),
            'config' => $config
        ]);
        $result = $this->email->createMessage()
            ->setRenderedHtml($templateHtml)
            ->to($toAddress)
            ->subject($subject)
            ->send();

        $sends = $result->getSends();
        if (empty($sends)) {
            throw new \RuntimeException('No emails were sent');
        }
        return $sends[0]->getId();
    }
}
