<?php

namespace App\Test\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ArticleRepository $repository;
    private string $path = '/article/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Article::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article index');

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
            'article[titreArticle]' => 'Testing',
            'article[dateArticle]' => 'Testing',
            'article[contentArticle]' => 'Testing',
            'article[nbrlikesArticle]' => 'Testing',
            'article[imageArticle]' => 'Testing',
            'article[categoryArticle]' => 'Testing',
            'article[iduser]' => 'Testing',
        ]);

        self::assertResponseRedirects('/article/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setTitreArticle('My Title');
        $fixture->setDateArticle('My Title');
        $fixture->setContentArticle('My Title');
        $fixture->setNbrlikesArticle('My Title');
        $fixture->setImageArticle('My Title');
        $fixture->setCategoryArticle('My Title');
        $fixture->setIduser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setTitreArticle('My Title');
        $fixture->setDateArticle('My Title');
        $fixture->setContentArticle('My Title');
        $fixture->setNbrlikesArticle('My Title');
        $fixture->setImageArticle('My Title');
        $fixture->setCategoryArticle('My Title');
        $fixture->setIduser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'article[titreArticle]' => 'Something New',
            'article[dateArticle]' => 'Something New',
            'article[contentArticle]' => 'Something New',
            'article[nbrlikesArticle]' => 'Something New',
            'article[imageArticle]' => 'Something New',
            'article[categoryArticle]' => 'Something New',
            'article[iduser]' => 'Something New',
        ]);

        self::assertResponseRedirects('/article/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitreArticle());
        self::assertSame('Something New', $fixture[0]->getDateArticle());
        self::assertSame('Something New', $fixture[0]->getContentArticle());
        self::assertSame('Something New', $fixture[0]->getNbrlikesArticle());
        self::assertSame('Something New', $fixture[0]->getImageArticle());
        self::assertSame('Something New', $fixture[0]->getCategoryArticle());
        self::assertSame('Something New', $fixture[0]->getIduser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Article();
        $fixture->setTitreArticle('My Title');
        $fixture->setDateArticle('My Title');
        $fixture->setContentArticle('My Title');
        $fixture->setNbrlikesArticle('My Title');
        $fixture->setImageArticle('My Title');
        $fixture->setCategoryArticle('My Title');
        $fixture->setIduser('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/article/');
    }
}
