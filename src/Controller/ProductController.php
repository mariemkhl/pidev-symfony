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
use App\Repository\CategoryRepository;
use App\Entity\Category;

use App\Entity\ProdCollect;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use App\Controller\LogoInterface;


use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use App\Controller\CalendarEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tattali\CalendarBundle\CalendarBundle;

use App\Service\Calendar;

#[Route('/product')]
class ProductController extends AbstractController  implements EventSubscriberInterface 
{


    #[Route('/home', name: 'home')]
    public function indexfront(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/homeFront.html.twig', [
            'controller_name' => 'homeController',
            'products' => $products,

        ]);
    }

    #[Route('/add_event', name: 'add_event', methods: ['POST'])]
    public function addEvent(Request $request): Response
{
    $title = $request->request->get('title');
    $start = new \DateTime($request->request->get('start'));
    $end = new \DateTime($request->request->get('end'));

    $event = new Event($title, $start, $end);

    

    $now = new \DateTime();
    $start = $now->setTime(0, 0, 0);
    $end = $now->setTime(23, 59, 59);
    $filters = [];

    $calendarEvent = new CalendarEvent($start, $end, $filters);

  
    $calendarEvent->addEvent($event);

 


    return $this->redirectToRoute('calendar');
        return $this->render('product/calendar.html.twig', [
            'calendar' => $calendarEvent,
            'event' => $event,
        ]);
    }


    public static function getSubscribedEvents()
    {
        return [];
    }
#[Route('/calendar', name: 'calendar')]
public function calendar()
    {



        $now = new \DateTime();
        $start = $now->setTime(0, 0, 0);
        $end = $now->setTime(23, 59, 59);
        $filters = [];
    
        $calendarEvent = new CalendarEvent($start, $end, $filters);
    
        // You can add events to the calendar using the addEvent method
        $calendarEvent->addEvent(new Event(
            'Event 1',
            new \DateTime('Tuesday this week'),
            new \DateTime('Wednesday this week')
        ));
    
        $calendarEvent->addEvent(new Event(
            'All day event',
            new \DateTime('Friday this week')
        ));

        $calendarEvent->addEvent(new Event(
            'Task 1',
            new \DateTime('tomorrow 10:00'),
            new \DateTime('tomorrow 12:00')
        ));

        
    
        return $this->render('product/calendar.html.twig', [
            'calendar' => $calendarEvent,
            
        ]);

      //  $now = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        // $calendarEvent = new CalendarEvent($now);

        // // You can add events to the calendar using the addEvent method
        // $calendarEvent->addEvent(new Event(
        //     'Event 1',
        //     new \DateTime('Tuesday this week'),
        //     new \DateTime('Wednesday this week')
        // ));

        // $calendarEvent->addEvent(new Event(
        //     'All day event',
        //     new \DateTime('Friday this week')
        // ));

        // return $this->render('product/calendar.html.twig', [
        //     'calendar' => $calendarEvent,
        // ]);

    }


    
    #[Route('/qr-code/{id}', name: 'product_qr_code', methods: ['GET'])]
    public function showQrCode(Product $product): Response
    {
        
        $qrCodeUrl = $product->getUrl();
            
    
        $writer = new PngWriter();
        $qrCode = QrCode::create($qrCodeUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        // $logo = Logo::create('public/assets/img/logo.png')
        //     ->setResizeToWidth(60);
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/assets/img/products/logo.png';
        if (file_exists($logoPath)) {
            $logo = Logo::create($logoPath)->setResizeToWidth(60);
        } else {
            $logo = null;
        }

        $label = Label::create('')->setFont(new NotoSans(8));
 
        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
        $qrCodes['simple'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('Simple')
                            )->getDataUri();
 
        $qrCode->setForegroundColor(new Color(255, 0, 0));
        $qrCodes['changeColor'] = $writer->write(
            $qrCode,
            null,
            $label->setText('Color Change')
        )->getDataUri();
 
        $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
        $qrCodes['changeBgColor'] = $writer->write(
            $qrCode,
            null,
            $label->setText('Background Color Change')
        )->getDataUri();
 
        $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
        $qrCodes['withImage'] = $writer->write(
            $qrCode,
            $logo,
            $label->setText('With Image')->setFont(new NotoSans(20))
        )->getDataUri();
 
        return $this->render('product/QRcode.html.twig', $qrCodes);
        return $this->render('product/QRcode.html.twig', [
                    'product' => $product,
                    'qrCodeData' => $qrCodeData,
                ]);
    }

    
   



    #[Route('/search', name: 'search')]
    public function serach(): Response
    {
        return $this->render('product/search.html.twig', [
            'controller_name' => 'homeController',
        ]);
    }


  #[Route('/searchP', name: 'searchProducts', methods: ['GET'])]
    public function searchy(ProductRepository $productRepository,CategoryRepository $categoryRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();
        $categoryName = $request->query->get('category');
        $categoryNames = $this->getDoctrine()->getRepository(Category::class)->createQueryBuilder('c')
        ->select('c.nom')
        ->orderBy('c.nom', 'ASC')
        ->getQuery();


        $searchTerm = $request->query->get('q');
        $products = $productRepository->createQueryBuilder('p')
            ->where('p.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
            //  $productRepository->findAll();








           
            $ranges = [];
            
        








        return $this->render('product/search.html.twig', [
            'products' => $products,
            'searchTerm' => $searchTerm,
            'categoryNames' => $categoryNames,
            'categories' => $categories,
            'ranges' => $ranges
        ]);
    }









    #[Route('/sortP', name: 'sortProducts', methods: ['GET'])]
    public function sortProduct(ProductRepository $productRepository,CategoryRepository $categoryRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();
        $categoryName = $request->query->get('category');
        $categoryNames = $this->getDoctrine()->getRepository(Category::class)->createQueryBuilder('c')
        ->select('c.nom')
        ->orderBy('c.nom', 'ASC')
        ->getQuery();


        $searchTerm = $request->query->get('q');
        $products = $productRepository->createQueryBuilder('p')
            ->where('p.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
            //  $productRepository->findAll();








            $em = $this->getDoctrine()->getManager();

            // Fetch all products from the database
            $products = $em->getRepository(Product::class)->findAll();
        
            // Sort the products by price
            usort($products, function ($a, $b) {
                return $a->getPrix() - $b->getPrix();
            });
        
            // Group the products by price range
            $ranges = [];
            $minPrice = 0;
            $maxPrice = 0;
        
            foreach ($products as $product) {
                if ($product->getPrix() < $minPrice || $minPrice == 0) {
                    $minPrice = $product->getPrix();
                }
        
                if ($product->getPrix() > $maxPrice) {
                    $maxPrice = $product->getPrix();
                }
        
                $rangeIndex = floor($product->getPrix() / 100);
        
                if (!isset($ranges[$rangeIndex])) {
                    $ranges[$rangeIndex] = [
                        'min' => $rangeIndex * 100,
                        'max' => ($rangeIndex + 1) * 100 - 1,
                        'products' => []
                    ];
                }
        
                $ranges[$rangeIndex]['products'][] = $product;
            }









        return $this->render('product/search.html.twig', [
            'products' => $products,
            'searchTerm' => $searchTerm,
            'categoryNames' => $categoryNames,
            'categories' => $categories,
            'ranges' => $ranges
        ]);
    }











    #[Route('/category/{category_id}', name: 'products_by_category')]
    public function filter(CategoryRepository $categoryRepository, $category_id, Request $request, ProductRepository $productRepository)
    {
        $categories = $categoryRepository->findAll();

        $searchTerm = $request->query->get('q');
        $products = $productRepository->createQueryBuilder('p')
        ->where('p.nom LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $searchTerm . '%')
        ->getQuery()
        ->getResult();
        
        $category = $categoryRepository->find($category_id);

    if (!$category) {
        throw $this->createNotFoundException('The category does not exist');
    }

    $products = $category->getProducts();

    $ranges = [];

    return $this->render('product/search.html.twig', [
        'products' => $products,
        'searchTerm' => $searchTerm,
        'categories' => $categories,
        'ranges' => $ranges,
    ]);

    }
    


#[Route('/prodlist', name: 'product_list')]
public function productListSorted( ProductRepository $productRepository): Response
{

    // Fetch all products from the database
    $products = $productRepository->findAll();

     // Sort the products by price
     usort($products, function ($a, $b) {
        return $a->getPrix() - $b->getPrix();
    });

    // Group the products by price range
    $ranges = [];
    $minPrice = 0;
    $maxPrice = 0;


    foreach ($products as $product) {
        if ($product->getPrix() < $minPrice || $minPrice == 0) {
            $minPrice = $product->getPrix();
        }

        if ($product->getPrix() > $maxPrice) {
            $maxPrice = $product->getPrix();
        }

        $rangeIndex = floor($product->getPrix() / 100);

        if (!isset($ranges[$rangeIndex])) {
            $ranges[$rangeIndex] = [
                'min' => $rangeIndex * 100,
                'max' => ($rangeIndex + 1) * 100 - 1,
                'products' => []
            ];
        }

        $ranges[$rangeIndex]['products'][] = $product;
    }

     
    // return $this->redirectToRoute('searchProducts', [], Response::HTTP_SEE_OTHER);
  
    // Pass the sorted products to your template
    return $this->render('product/search.html.twig', [
        'products' => $products,
        'ranges' => $ranges,

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
    public function indexcart(ProductRepository $productRepository,Request $request): Response
    {

        $products = $productRepository->findAll();

$productId = $request->query->get('id');

$product = $productRepository->find($productId);

return $this->render('product/cart.html.twig', [
    'controller_name' => 'cartController',
    'product' => $product, 
    'products' => $products, 
]);

        // return $this->render('product/cart.html.twig', [
        //     'controller_name' => 'cartController',
           
        // ]);
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
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('q');
        $products = $productRepository->createQueryBuilder('p')
            ->where('p.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
            //  $productRepository->findAll();

        return $this->render('product/shop.html.twig', [
            'products' => $products,
            'searchTerm' => $searchTerm,
            
        ]);

    //    return $this->render('product/shop.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    }


    // #[Route('/', name: 'app_product_index', methods: ['GET'])]
    // public function index(ProductRepository $productRepository): Response
    // {
    //     return $this->render('product/index.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    // }


//FRONT
 #[Route('/addProduct.html', name: 'addProduct', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

      


            $file = $form->get('img')->getData();

            $fileName = uniqid().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // Handle exception
            }

            // Set the file name to the product entity
            $product->setImg($fileName);




       $productRepository->save($product, true);

            return $this->redirectToRoute('shop', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/addProdForm.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

//BACK
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




//FRONT
    // #[Route('/search', name: 'search_products', methods: ['GET', 'POST'])]
    // public function search(Request $request)
    // {
    //     $searchTerm = $request->query->get('q');

    //     $entityManager = $this->getDoctrine()->getManager();
    //     $products = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
    //         ->where('p.name LIKE :searchTerm')
    //         ->setParameter('searchTerm', '%' . $searchTerm . '%')
    //         ->getQuery()
    //         ->getResult();

    //     return $this->render('product/shop.html.twig', [
    //         'products' => $products,
    //         'searchTerm' => $searchTerm,
    //     ]);
    // }
   

    
  

}



