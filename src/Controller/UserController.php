<?php

namespace App\Controller;

// use App\Entity\Profile;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\UserProfilePictureType;
use App\Form\UserType;
use App\Repository\DirectorateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

   
    #[Route('/profiles', name: 'my_psrofile', methods: ['GET','POST'])]
    public function rofile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $user = $this->getUser();
        // if($user->getProfile()==''){
        //     $userInfo=new Profile();
        //     $profileform = $this->createForm(ProfileType::class, $userInfo); 
        //     $profileform->handleRequest($request);
        //     $userInfo->setUser($user);
        //     $entityManager->persist($userInfo);
        //     $entityManager->flush();
        // }
        // else{
        //  $userInfo=$user->getProfile();
        // }
        $userInfo=$user->getProfile();
        $profileform = $this->createForm(ProfileType::class, $userInfo);
        if ($profileform->isSubmitted() && $profileform->isValid()){
                $entityManager->persist($profileform);
                $entityManager->flush();
            $this->addFlash('success', "Profile picture  has been updated successfully!");
        }
        $profilepictureform = $this->createForm(UserProfilePictureType::class, $userInfo);
        // $profilepictureform->handleRequest($request);
        if ($profilepictureform->isSubmitted() && $profilepictureform->isValid()) {
            $prifilepicture = $profilepictureform->get('image')->getData();
             if ($prifilepicture == NULL) {
                echo 'Image not uploaded';
                 $prifilepicture = '';
            } else {
                 $fileName3 = 'DP-'.  md5(uniqid()) . '.' . $prifilepicture->guessExtension();
                $prifilepicture->move($this->getParameter('profile_pictures'), $fileName3);
                $userInfo->setImage($fileName3);
                $entityManager->persist($profilepictureform);
                $entityManager->flush();
            $this->addFlash('success', "Profile picture  has been updated successfully!   ");
 
            }
        }
      return $this->render('user/profile.html.twig', [
            // 'published_research' => $publishedResearch,
            'user' => $user,
            'allform' => $profileform->createView(),
            'form' => $profilepictureform->createView(),
        ]);
    }
 
    #[Route('/batchimport', name: 'generate_batchimport', methods: ['GET', 'POST'])]
    public function batchimport( DirectorateRepository $directorateRepository,
     EntityManagerInterface $entityManager): Response
    {
         
                $totalPossiblecoupons=   100;
               
                $alldirectorates=  $directorateRepository->findAll();
                 
            foreach ($alldirectorates as  $value) {
                for ($i=0; $i < $totalPossiblecoupons; $i++) {  
                $coupon = new User(); 

                 $coupon->setEmail("f".$i."irew".$value->getAcronym().".legese74". $i."@gmail.com" );
                 $coupon->setDirectorate($value ); 
                 $coupon->setRoles(["ROLE_ADMIN"]);
                 $coupon->setPassword($i."frew.legese@gmail.com");
                $entityManager->persist($coupon); 
                $entityManager->flush();
                }
            }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

        
    }
    
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
