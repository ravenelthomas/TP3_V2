<?php

namespace App\Test\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SessionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SessionRepository $repository;
    private string $path = '/admin/session/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Session::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Session index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'session[start_session]' => 'Testing',
            'session[end_session]' => 'Testing',
            'session[commentary]' => 'Testing',
            'session[response]' => 'Testing',
            'session[id_user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/admin/session/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Session();
        $fixture->setStart_session('My Title');
        $fixture->setEnd_session('My Title');
        $fixture->setCommentary('My Title');
        $fixture->setResponse('My Title');
        $fixture->setId_user('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Session');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Session();
        $fixture->setStart_session('My Title');
        $fixture->setEnd_session('My Title');
        $fixture->setCommentary('My Title');
        $fixture->setResponse('My Title');
        $fixture->setId_user('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'session[start_session]' => 'Something New',
            'session[end_session]' => 'Something New',
            'session[commentary]' => 'Something New',
            'session[response]' => 'Something New',
            'session[id_user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/session/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getStart_session());
        self::assertSame('Something New', $fixture[0]->getEnd_session());
        self::assertSame('Something New', $fixture[0]->getCommentary());
        self::assertSame('Something New', $fixture[0]->getResponse());
        self::assertSame('Something New', $fixture[0]->getId_user());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Session();
        $fixture->setStart_session('My Title');
        $fixture->setEnd_session('My Title');
        $fixture->setCommentary('My Title');
        $fixture->setResponse('My Title');
        $fixture->setId_user('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/admin/session/');
    }
}
