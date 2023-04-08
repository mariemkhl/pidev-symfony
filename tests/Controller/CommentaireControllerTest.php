<?php

namespace App\Test\Controller;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentaireControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CommentaireRepository $repository;
    private string $path = '/commentaire/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Commentaire::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commentaire index');

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
            'commentaire[idArticle]' => 'Testing',
            'commentaire[contentCommentaire]' => 'Testing',
            'commentaire[dateCommentaire]' => 'Testing',
            'commentaire[nbLikesCommentaire]' => 'Testing',
            'commentaire[etatCommentaire]' => 'Testing',
            'commentaire[idUser]' => 'Testing',
        ]);

        self::assertResponseRedirects('/commentaire/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commentaire();
        $fixture->setIdArticle('My Title');
        $fixture->setContentCommentaire('My Title');
        $fixture->setDateCommentaire('My Title');
        $fixture->setNbLikesCommentaire('My Title');
        $fixture->setEtatCommentaire('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commentaire');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commentaire();
        $fixture->setIdArticle('My Title');
        $fixture->setContentCommentaire('My Title');
        $fixture->setDateCommentaire('My Title');
        $fixture->setNbLikesCommentaire('My Title');
        $fixture->setEtatCommentaire('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'commentaire[idArticle]' => 'Something New',
            'commentaire[contentCommentaire]' => 'Something New',
            'commentaire[dateCommentaire]' => 'Something New',
            'commentaire[nbLikesCommentaire]' => 'Something New',
            'commentaire[etatCommentaire]' => 'Something New',
            'commentaire[idUser]' => 'Something New',
        ]);

        self::assertResponseRedirects('/commentaire/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getIdArticle());
        self::assertSame('Something New', $fixture[0]->getContentCommentaire());
        self::assertSame('Something New', $fixture[0]->getDateCommentaire());
        self::assertSame('Something New', $fixture[0]->getNbLikesCommentaire());
        self::assertSame('Something New', $fixture[0]->getEtatCommentaire());
        self::assertSame('Something New', $fixture[0]->getIdUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Commentaire();
        $fixture->setIdArticle('My Title');
        $fixture->setContentCommentaire('My Title');
        $fixture->setDateCommentaire('My Title');
        $fixture->setNbLikesCommentaire('My Title');
        $fixture->setEtatCommentaire('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/commentaire/');
    }
}
