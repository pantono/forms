<?php

namespace Pantono\Forms\Actions;

use Pantono\Forms\Model\FormSubmission;
use GuzzleHttp\Client;
use Pantono\Logger\Factory\LoggedHttpClientFactory;

class WebhookActionController extends AbstractActionController
{
    public function execute(FormSubmission $formSubmission, array $config = []): string
    {
        $method = $config['method'] ?? 'POST';
        $url = $config['url'] ?? null;
        $headers = $config['headers'] ?? [];
        if (!$url) {
            throw new \RuntimeException('No URL specified for webhook action');
        }
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \RuntimeException('Invalid URL specified for webhook action');
        }
        $response = $this->createClient('webhook')
            ->request($method, $url, ['headers' => $headers]);

        return $response->getBody()->getContents();
    }

    private function createClient(string $name): Client
    {
        $factory = new LoggedHttpClientFactory($name);
        return $factory->createInstance();
    }
}
