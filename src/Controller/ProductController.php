<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Category;

use App\Entity\ProdCollect;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{


    #[Route('/home', name: 'home')]
    public function indexfront(): Response
    {
        return $this->render('product/homeFront.html.twig', [
            'controller_name' => 'homeController',
        ]);
    }

    #[Route('/homeAdmin', name: 'prod_cat_col')]
    public function indexfrontAdmin(): Response
    {
        return $this->render('/productFront.html.twig', [
            'controller_name' => 'homeController',
        ]);
    }
    #[Route('/prodcatcolAdmin', name: 'homeAdmin')]
    public function prodfrontAdmin(): Response
    {
        return $this->render('productAdmin/homeFront.html.twig', [
            'controller_name' => 'homeController',
        ]);
    }
    #[Route('/prodformAdmin', name: 'prodformAdmin')]
    public function prodfromAdmin(): Response
    {
        return $this->render('productAdmin/productsForm.html.twig', [
            'controller_name' => 'homeController',
        ]);
    }

 

    #[Route('/shop.html ', name: 'shop')]
    public function indexshop(): Response
    {
        return $this->render('product/shop.html.twig', [
            'controller_name' => 'shopController',
        ]);
    }
    #[Route('/listproduct ', name: 'listproduct')]
    public function listprod(ProductRepository $productRepository): Response
    {
        return $this->render('productAdmin/products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
    
    #[Route('/listprod', name: 'listprod', methods: ['POST'])]
    public function index2(ProductRepository $productRepository): Response
    {
        return $this->render('productAdmin/products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

  


    #[Route('/cart.html ', name: 'cart')]
    public function indexcart(): Response
    {
        return $this->render('product/cart.html.twig', [
            'controller_name' => 'cartController',
           
        ]);
    }


    #[Route('/checkForm.html ', name: 'chekform')]
    public function indexCheckForm(): Response
    {
        return $this->render('product/checkForm.html.twig', [
            'controller_name' => 'formController',
        ]);
    }

    #[Route('/singleProduct.html ', name: 'singleproduct')]
    public function indexSingleProduct(): Response
    {
        return $this->render('product/singleProduct.html.twig', [
            'controller_name' => 'SPController',
        ]);
    }




    // #[Route('/addProduct.html ', name: 'addProduct')]
    // public function addProduct(): Response
    // {
    //     return $this->render('product/addProdForm.html.twig', [
    //         'controller_name' => 'APController',
    //     ]);
    // }





    #[Route('/shop.html', name: 'shop', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/shop.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }


    // #[Route('/', name: 'app_product_index', methods: ['GET'])]
    // public function index(ProductRepository $productRepository): Response
    // {
    //     return $this->render('product/index.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    // }


 #[Route('/addProduct.html', name: 'addProduct', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

       $productRepository->save($product, true);

            return $this->redirectToRoute('shop', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/addProdForm.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }


    #[Route('/prodformAdmin', name: 'prodformAdmin', methods: ['GET', 'POST'])]
    public function new1(Request $request, ProductRepository $productRepository ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

       $productRepository->save($product, true);

            return $this->redirectToRoute('listproduct', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('productAdmin/productsForm.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    
   
    


    // #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, ProductRepository $productRepository): Response
    // {
    //     $product = new Product();
    //     $form = $this->createForm(ProductType::class, $product);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $productRepository->save($product, true);

    //         return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('product/new.html.twig', [
    //         'product' => $product,
    //         'form' => $form,
    //     ]);
    // }





    #[Route('/{id}', name: 'singleproduct1', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/singleProduct.html.twig', [
            'product' => $product,
        ]);
    }
  


    #[Route('/detail/{id}', name: 'prod_detail', methods: ['GET'])]
    public function showprod(Product $product): Response
    {
        return $this->render('productAdmin/oneProduct.html.twig', [
            'product' => $product,
        ]);
    }



    // #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    // public function show(Product $product): Response
    // {
    //     return $this->render('product/show.html.twig', [
    //         'product' => $product,
    //     ]);
    // }


//FRONT

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('shop', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/updateProdForm.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    //BACK
    #[Route('/prodedit/{id}', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit1(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('listproduct', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('productAdmin/productEditForm.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }



    // #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    // {
    //     $form = $this->createForm(ProductType::class, $product);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $productRepository->save($product, true);

    //         return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('product/edit.html.twig', [
    //         'product' => $product,
    //         'form' => $form,
    //     ]);
    // }




    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('shop', [], Response::HTTP_SEE_OTHER);

        // return $this->render('product/shop.html.twig', [
        //     'products' => $productRepository->findAll(),
        // ]);
    }

    #[Route('/admindelete/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete1(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('listproduct', [], Response::HTTP_SEE_OTHER);

        // return $this->render('product/shop.html.twig', [
        //     'products' => $productRepository->findAll(),
        // ]);
    }

    // #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    // public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
    //         $productRepository->remove($product, true);
    //     }

    //     return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    // }
}
