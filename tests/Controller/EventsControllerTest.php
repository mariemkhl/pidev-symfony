<?php

namespace App\Test\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EventsRepository $repository;
    private string $path = '/events/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Events::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event index');

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
            'event[nameev]' => 'Testing',
            'event[dateEvent]' => 'Testing',
            'event[location]' => 'Testing',
            'event[idUser]' => 'Testing',
            'event[categorie]' => 'Testing',
            'event[nbplacetotal]' => 'Testing',
            'event[img]' => 'Testing',
        ]);

        self::assertResponseRedirects('/events/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Events();
        $fixture->setNameev('My Title');
        $fixture->setDateEvent('My Title');
        $fixture->setLocation('My Title');
        $fixture->setIdUser('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setNbplacetotal('My Title');
        $fixture->setImg('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Events();
        $fixture->setNameev('My Title');
        $fixture->setDateEvent('My Title');
        $fixture->setLocation('My Title');
        $fixture->setIdUser('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setNbplacetotal('My Title');
        $fixture->setImg('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'event[nameev]' => 'Something New',
            'event[dateEvent]' => 'Something New',
            'event[location]' => 'Something New',
            'event[idUser]' => 'Something New',
            'event[categorie]' => 'Something New',
            'event[nbplacetotal]' => 'Something New',
            'event[img]' => 'Something New',
        ]);

        self::assertResponseRedirects('/events/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNameev());
        self::assertSame('Something New', $fixture[0]->getDateEvent());
        self::assertSame('Something New', $fixture[0]->getLocation());
        self::assertSame('Something New', $fixture[0]->getIdUser());
        self::assertSame('Something New', $fixture[0]->getCategorie());
        self::assertSame('Something New', $fixture[0]->getNbplacetotal());
        self::assertSame('Something New', $fixture[0]->getImg());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Events();
        $fixture->setNameev('My Title');
        $fixture->setDateEvent('My Title');
        $fixture->setLocation('My Title');
        $fixture->setIdUser('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setNbplacetotal('My Title');
        $fixture->setImg('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/events/');
    }
}
