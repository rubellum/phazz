<?php

namespace Tests\AcceptanceTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JobRegisterTest extends WebTestCase
{
    public function test_JobRegister_OK()
    {
        $client = static::createClient();

        // job register
        $crawler = $client->request('GET', '/job/register');
        $this->assertResponseIsSuccessful('/job/register is not successful');
        $token = $crawler->filter('[data-form-token]')->attr('data-form-token');

        $client->request('POST', '/api/job/save', [], [], [], json_encode([
            'token' => $token,
            'id' => null,
            'name' => 'test-site',
            'url' => 'https://rubellum.jp/',
            'type' => 'single',
            'operation' => [
                'single' => [
                    "fields" => [
                        [
                            "name" => "title",
                            "type" => "text",
                            "converter" => [
                                "trim",
                            ],
                            "selector" => "//head/title/text()",
                        ]
                    ],
                ]
            ],
        ]));
        $this->assertResponseIsSuccessful('/api/job/save is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($responseJson['success']);
    }

    /**
     * @return void
     * @depends test_JobRegister_OK
     */
    public function test_ShowJobPages_OK()
    {
        $client = static::createClient();

        // search jobs
        $client->request('GET', '/api/job/search');
        $this->assertResponseIsSuccessful('/api/job/search is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseJson['items']);

        // show job edit view
        $client->request('GET', $responseJson['items'][0]['edit_link']);
        $this->assertResponseIsSuccessful($responseJson['items'][0]['edit_link'] . ' is not successful');

        // show job detail view
        $client->request('GET', $responseJson['items'][0]['detail_link']);
        $this->assertResponseIsSuccessful($responseJson['items'][0]['detail_link'] . ' is not successful');

        // job execution
        $client->request('GET', $responseJson['items'][0]['execution_link']);
        $this->assertResponseIsSuccessful($responseJson['items'][0]['execution_link'] . ' is not successful');
    }

    /**
     * @return void
     * @depends test_ShowJobPages_OK
     */
    public function test_JobExecution_OK()
    {
        $client = static::createClient();

        // search jobs
        $client->request('GET', '/api/job/search');
        $this->assertResponseIsSuccessful('/api/job/search is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseJson['items']);
        $jobId = $responseJson['items'][0]['id'];

        // show job execution form
        $crawler = $client->request('GET', $responseJson['items'][0]['execution_link']);
        $this->assertResponseIsSuccessful($responseJson['items'][0]['execution_link'] . ' is not successful');
        $token = $crawler->filter('[data-form-token]')->attr('data-form-token');

        // job execution
        $client->request('POST', '/api/job/execution/save', [], [], [], json_encode([
            'token' => $token,
            'id' => $jobId,
        ]));
        $this->assertResponseIsSuccessful('/api/job/execution/save is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($responseJson['success']);
    }

    public function test_ShowTaskPages_OK()
    {
        $client = static::createClient();

        // search tasks
        $client->request('GET', '/api/task/search');
        $this->assertResponseIsSuccessful('/api/task/search is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseJson['items']);
        $this->assertFalse($responseJson['items'][0]['showDownloadLink']);

        // show task edit view
        $client->request('GET', $responseJson['items'][0]['detailLink']);
        $this->assertResponseIsSuccessful($responseJson['items'][0]['detailLink'] . ' is not successful');

        // search task confirm pages
        $client->request('GET', '/api/task/confirm', [
            'id' => $responseJson['items'][0]['id'],
        ]);
        $this->assertResponseIsSuccessful('/api/task/confirm is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('Queued', $responseJson['item']['state']);
    }

    public function test_TaskExecution_OK()
    {
        $client = static::createClient();

        // search tasks
        $client->request('GET', '/api/task/search');
        $this->assertResponseIsSuccessful('/api/task/search is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $taskId = $responseJson['items'][0]['id'];

        // show task edit view
        $crawler = $client->request('GET', $responseJson['items'][0]['detailLink']);
        $this->assertResponseIsSuccessful($responseJson['items'][0]['detailLink'] . ' is not successful');
        $token = $crawler->filter('[data-form-token]')->attr('data-form-token');

        // task execution
        $client->request('POST', '/api/task/run', [], [], [], json_encode([
            'token' => $token,
            'id' => $taskId,
        ]));
        $this->assertResponseIsSuccessful('/api/task/run is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($responseJson['success']);

        // download link on search page
        $client->request('GET', '/api/task/search');
        $this->assertResponseIsSuccessful('/api/task/search is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($responseJson['items'][0]['showDownloadLink']);

        // download link on detail page
        $client->request('GET', '/api/task/confirm', [
            'id' => $taskId,
        ]);
        $this->assertResponseIsSuccessful('/api/task/confirm is not successful');
        $responseJson = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('Success', $responseJson['item']['state']);
        $this->assertTrue($responseJson['item']['showDownloadLink']);
        $this->assertFalse($responseJson['item']['showRunLink']);
    }
}
