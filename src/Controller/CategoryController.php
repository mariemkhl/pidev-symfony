<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


#[Route('/category')]
class CategoryController extends AbstractController
{

    // #[Route('/', name: 'app_category_index', methods: ['GET'])]
    // public function index(CategoryRepository $categoryRepository): Response
    // {
    //     return $this->render('category/index.html.twig', [
    //         'categories' => $categoryRepository->findAll(),
    //     ]);
    // }

    #[Route('/listcateg', name: 'listcateg')]
    public function listCategory(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/categories.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }


// #[Route('/catformAdmin', name: 'catformAdmin')]
    // public function catfromAdmin(): Response
    // {
    //     return $this->render('productAdmin/categoriesForm.html.twig', [
    //         'controller_name' => 'homeController',
    //     ]);
    // }






   #[Route('/catformAdmin', name: 'catformAdmin', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('listcateg', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/categoriesForm.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }


    // #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, CategoryRepository $categoryRepository): Response
    // {
    //     $category = new Category();
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $categoryRepository->save($category, true);

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('category/new.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/catdetail/{id}', name: 'cat_detail', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/oneCategory.html.twig', [
            'category' => $category,
        ]);
    }

    // #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    // public function showcat(Category $category): Response
    // {
    //     return $this->render('category/show.html.twig', [
    //         'category' => $category,
    //     ]);
    // }


    // #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    // {
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $categoryRepository->save($category, true);

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('category/edit.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/catedit/{id}', name: 'cat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        try {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('listcateg', [], Response::HTTP_SEE_OTHER);

 }

} catch (ForeignKeyConstraintViolationException $e) {
    // Display an error message in a window using HTML and redirect to the previous page
    $errorMessage = 'Cannot delete category "'.$category->getName().'". There are one or more products associated with this category.';
    $this->addFlash('error', $errorMessage);
    return $this->redirectToRoute('cat_edit', ['id' => $category->getId()]);
}

        return $this->renderForm('category/catEditForm.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }





    // #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    // public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
    //         $categoryRepository->remove($category, true);
    //     }

    //     return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    // }

    #[Route('/{id}', name: 'cat_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('listcateg', [], Response::HTTP_SEE_OTHER);
    }

}
