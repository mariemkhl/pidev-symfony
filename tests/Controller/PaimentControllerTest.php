<?php

namespace App\Test\Controller;

use App\Entity\Paiment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaimentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/paiment/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Paiment::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Paiment index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'paiment[numCarte]' => 'Testing',
            'paiment[nomCarte]' => 'Testing',
            'paiment[dateEx]' => 'Testing',
            'paiment[cvCode]' => 'Testing',
            'paiment[prixTot]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paiment();
        $fixture->setNumCarte('My Title');
        $fixture->setNomCarte('My Title');
        $fixture->setDateEx('My Title');
        $fixture->setCvCode('My Title');
        $fixture->setPrixTot('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Paiment');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paiment();
        $fixture->setNumCarte('Value');
        $fixture->setNomCarte('Value');
        $fixture->setDateEx('Value');
        $fixture->setCvCode('Value');
        $fixture->setPrixTot('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'paiment[numCarte]' => 'Something New',
            'paiment[nomCarte]' => 'Something New',
            'paiment[dateEx]' => 'Something New',
            'paiment[cvCode]' => 'Something New',
            'paiment[prixTot]' => 'Something New',
        ]);

        self::assertResponseRedirects('/paiment/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNumCarte());
        self::assertSame('Something New', $fixture[0]->getNomCarte());
        self::assertSame('Something New', $fixture[0]->getDateEx());
        self::assertSame('Something New', $fixture[0]->getCvCode());
        self::assertSame('Something New', $fixture[0]->getPrixTot());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paiment();
        $fixture->setNumCarte('Value');
        $fixture->setNomCarte('Value');
        $fixture->setDateEx('Value');
        $fixture->setCvCode('Value');
        $fixture->setPrixTot('Value');

        $$this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/paiment/');
        self::assertSame(0, $this->repository->count([]));
    }
}
