<?php

namespace App\Controller;
use App\Entity\MapArt;
use App\Entity\Utilisateur;
use App\Form\MapArtType;
use App\Repository\mapRepository;
use App\Repository\utilisateurRepository;
use App\Repository\likesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Likes;
use Symfony\Component\Form\FormError;
use phpseclib3\Net\SFTP;
use Symfony\Component\HttpFoundation\File\File;
class MapController extends AbstractController
{
    #[Route('/mapart', name: 'app_map')]
    public function index(Request $request,Request $request1, mapRepository $mapRepository): Response
    {   $marks=$mapRepository->findAll();
        $sftp = new SFTP('172.20.10.8');
        // $sftp = new \phpseclib3\Net\SFTP('192.168.1.164');
       if (!$sftp->login('iheb', '190922')) {
    // Failed to login
      throw new \Exception('Failed to login to SFTP server');
      }

      // Set the path on the server to upload the file to
      $remotePath = '../';
         foreach ( $marks as $place ){
      // Get the path to the local file to upload
        $localPath = '../public/assets/img/'.$place->getImage();
        $contents = $sftp->get('../'.$place->getImage(),$localPath);
         }
//header('Content-Type: image/jpeg');
//echo $contents;
// Close the SFTP connection
//$sftp->disconnect();


        $mapArt = new MapArt();
        $form = $this->createForm(MapArtType::class, $mapArt);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $empla=$mapRepository->findOneBy(array('nomplace'=>$mapArt->getNomplace()));
            if($empla == null){
            $marks2=$mapRepository->findAll();
            $mapArt2 = new MapArt();
            $form2 = $this->createForm(MapArtType::class, $mapArt);
            $mapRepository->save($mapArt, true);
            $localPath = '../public/assets/img/'.$mapArt->getImage();

// Upload the file to the server
   if (!$sftp->put($remotePath . basename($localPath), $localPath, SFTP::SOURCE_LOCAL_FILE)) {
    // Failed to upload file
    throw new \Exception('Failed to upload file to SFTP server');
    }
  

            return $this->redirectToRoute('app_map');
    } elseif(($form->get('categorie')->getData() != 'cinema')){
        $form['categorie']->addError(new FormError('categorie should be either cinema , theatre or museum'));
    } elseif(($form->get('categorie')->getData() != 'theatre')){
        $form['categorie']->addError(new FormError('categorie should be either cinema , theatre or museum'));
    }
    else{$form['nomplace']->addError(new FormError('name already exist'));
        return $this->renderForm('map/index.html.twig', [
        
            'controller_name' => 'MapController',
            'form' => $form,'marks'=>$marks,'marklength'=>count($marks)
        ]);
     }
}
    return $this->renderForm('map/index.html.twig', [
        
        'controller_name' => 'MapController',
        'form' => $form,'marks'=>$marks,'marklength'=>count($marks)
    ]);
    }
    #[Route('/mapart/{id}',name: 'mapupdate')]
    public function updatemap($id,mapRepository $repo,utilisateurRepository $repo2,ManagerRegistry $doctrine,Request $req){
        $marker = $repo->find($id);
           $imagePath = '../public/assets/img/'.$marker->getImage();

        $imageFile = new File($imagePath);
       $marker->setImageFile($imageFile);
        $user =$repo2->find(67);
       $marker->setNblikes($marker->getNblikes()+1);
        $entitymanager = $doctrine->getManager();
        $entitymanager->persist($marker);
        $entitymanager->flush();
        $like = new Likes();
        $like->setRefl($id);
        $like->setRefu($user);
        $like->setNomu($user->getUsername());
        $entitymanager->persist($like);
            $entitymanager->flush();
        
        return $this->redirectToRoute('app_map');
       
    }
    #[Route('/mapart2/{id}',name: 'likedelete')]
    public function deletelike($id,likesRepository $repo2,mapRepository $repo,ManagerRegistry $doctrine,Request $req){
        $marker = $repo->find($id);
        $imagePath = '../public/assets/img/'.$marker->getImage();

        $imageFile = new File($imagePath);
       $marker->setImageFile($imageFile);
        if($marker->getNblikes() > 0){
       $marker->setNblikes($marker->getNblikes()-1);
        $entitymanager = $doctrine->getManager();
        $entitymanager->persist($marker);
        $entitymanager->flush();
        $like = $repo2->findOneBy(array('refl'=>$id,'refu'=>67));
        
        $entitymanager->remove($like);
        $entitymanager->flush();
    }
        return $this->redirectToRoute('app_map');
       
    }
    #[Route('/mapart3/{id}',name: 'mapdelete')]
    public function deletemap($id,likesRepository $repo2,mapRepository $repo,ManagerRegistry $doctrine,Request $req){
        $marker = $repo->find($id);
      //  $imagePath = '../public/assets/img/'.$marker->getImage();

        //$imageFile = new File($imagePath);
       //$marker->setImageFile($imageFile);
        $entitymanager = $doctrine->getManager();
        $entitymanager->remove($marker);
        $entitymanager->flush();
        $like = $repo2->findBy(array('refl'=>$id));
        foreach( $like as $likes){
        $entitymanager->remove($likes);
        $entitymanager->flush();
        }
    
        return $this->redirectToRoute('app_map');
       
    }

}
