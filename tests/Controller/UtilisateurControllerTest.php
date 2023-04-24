<?php

namespace App\Test\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilisateurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UtilisateurRepository $repository;
    private string $path = '/utilisateur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Utilisateur::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur index');

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
            'utilisateur[username]' => 'Testing',
            'utilisateur[password]' => 'Testing',
            'utilisateur[email]' => 'Testing',
            'utilisateur[address]' => 'Testing',
            'utilisateur[domaine]' => 'Testing',
            'utilisateur[numTel]' => 'Testing',
            'utilisateur[role]' => 'Testing',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setUsername('My Title');
        $fixture->setPassword('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAddress('My Title');
        $fixture->setDomaine('My Title');
        $fixture->setNumTel('My Title');
        $fixture->setRole('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setUsername('My Title');
        $fixture->setPassword('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAddress('My Title');
        $fixture->setDomaine('My Title');
        $fixture->setNumTel('My Title');
        $fixture->setRole('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'utilisateur[username]' => 'Something New',
            'utilisateur[password]' => 'Something New',
            'utilisateur[email]' => 'Something New',
            'utilisateur[address]' => 'Something New',
            'utilisateur[domaine]' => 'Something New',
            'utilisateur[numTel]' => 'Something New',
            'utilisateur[role]' => 'Something New',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getUsername());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getDomaine());
        self::assertSame('Something New', $fixture[0]->getNumTel());
        self::assertSame('Something New', $fixture[0]->getRole());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Utilisateur();
        $fixture->setUsername('My Title');
        $fixture->setPassword('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAddress('My Title');
        $fixture->setDomaine('My Title');
        $fixture->setNumTel('My Title');
        $fixture->setRole('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/utilisateur/');
    }
}
