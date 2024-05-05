<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Form\UserProfilePictureType;
// use App\Form\UserProfilePictureType;

use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/profile')]
class ProfileController extends AbstractController
{

    #[Route('/', name: 'my_profile', methods: ['GET', 'POST'])]
    public function userprofile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $user = $this->getUser();
        if ($this->getUser()->getProfile()) {
            $user = $user->getProfile();
        } else {

            $user = new Profile();
            $user->setUser($this->getUser());
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUser($this->getUser());
            $entityManager->persist($user);

            $entityManager->flush();
            $this->addFlash('success', "Your personal information has been updated successfully!");

            return $this->redirectToRoute('my_profile');
        }
        $profile = $this->getUser()->getProfile();
        $profilepictureform = $this->createForm(UserProfilePictureType::class, $profile);
        $profilepictureform->handleRequest($request);
        // dd();
        if ($profilepictureform->isSubmitted() && $profilepictureform->isValid()) {
            $prifilepicture = $profilepictureform->get('image')->getData();
            // dd();
            if ($prifilepicture == NULL) {
                echo 'Image not uploaded';
                $prifilepicture = '';
            } else {
                $fileName3 = 'PP-' .  md5(uniqid()) . '.' . $prifilepicture->guessExtension();
                $prifilepicture->move($this->getParameter('profile_pictures'), $fileName3);
                $profile->setImage($fileName3);
                $entityManager->persist($profile);
                $entityManager->flush();
                $this->addFlash('success', "Profile picture  has been changed successfully!   ");
            }
        }
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'allform' => $form->createView(),
            'profileform' => $profilepictureform->createView(),
        ]);
    }





    // #[Route('/{id}/details', name: 'app_profile_show', methods: ['GET'])]
    // public function show(Profile $profile): Response
    // {
    //     return $this->render('profile/show.html.twig', [
    //         'profile' => $profile,
    //     ]);
    // }

    // #[Route('/pic', name: 'my_eprofdile', methods: ['GET', 'POST'])]
    // public function picrdofile(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $this->denyAccessUnlessGranted("ROLE_USER");
    //     //   dd($this->getUser()->getProfile());
    //     $profile = $this->getUser()->getProfile();
    //             if ($profile) {
    //         // $profile = $user->getProfile();
    //         // dd($profile);
    //     } else {
    //         dd();
    //         $profile = new Profile();
    //         $profile->setUser($this->getUser());
    //     }
    //     // dd($user);
    //     // $profile = $this->getUser()->getProfile();
    //     $profilepictureform = $this->createForm(UserProfilePictureType::class, $profile);
    //     $profilepictureform->handleRequest($request);
    //     // dd();
    //     if ($profilepictureform->isSubmitted()  ) {
    //         $prifilepicture = $profilepictureform->get('image')->getData();
    //         // dd($profilepictureform);
    //         if ($prifilepicture == NULL) {
    //             echo 'Image not uploaded';
    //             $prifilepicture = '';
    //         } else {
    //             $fileName3 = 'PP-' .  md5(uniqid()) . '.' . $prifilepicture->guessExtension();
    //             $prifilepicture->move($this->getParameter('profile_pictures'), $fileName3);
    //             $profile->setImage($fileName3);
    //             $entityManager->persist($profile);
    //             $entityManager->flush();
    //             $this->addFlash('success', "Profile picture  has been changed successfully!   ");
    //         }
    //     }

    //     return $this->render('user/profile2.html.twig', [
    //         // 'user' => $user,
    //         'profileform' => $profilepictureform,
    //     ]);
    // }



    // #[Route('/{id}/delete', name: 'app_profile_delete', methods: ['POST'])]
    // public function delete(Request $request, Profile $profile, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $profile->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($profile);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    // }
}
