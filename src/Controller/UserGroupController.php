<?php

namespace App\Controller;

use App\Entity\UserGroup;
use App\Form\UserGroupType;
use App\Repository\PermissionRepository;
use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user-group')]
class UserGroupController extends AbstractController
{
    #[Route('/', name: 'app_user_group_index', methods:  ['GET', 'POST'])]
    public function index(UserGroupRepository $userGroupRepository, PaginatorInterface $paginator, Request $request): Response
    {

         
    $queryBuilder = $userGroupRepository->findAll();
    // ['name' => $request->query->get('search')]);
    // $queryBuilder = $userGroupRepository->findBy(['name' => $request->query->get('search')]);
          $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );


        
        return $this->render('user_group/index.html.twig', [
            'user_groups' => $data,
        ]);
    }

    #[Route('/new', name: 'app_user_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userGroup = new UserGroup();
        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userGroup);
            $entityManager->flush();

            return $this->redirectToRoute('user_group_users',['id'=>$userGroup->getId()]);

            // return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_group/new.html.twig', [
            'user_group' => $userGroup,
            'form' => $form,
        ]);
    }

 
    #[Route('/{id}/users', name: 'user_group_users')]

    public function groupuser(UserGroup $userGroup,  Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager){
        // $this->denyAccessUnlessGranted('ad_usr_to_grp');

       if($request->request->get('usergroupuser')){
           $users=$userGroup->getUsers();
              foreach ($users as $user) {
            $userGroup->removeUser($user);
           }
           
        //    $users=$userRepository->findAll();
           $users=$userRepository->findBy(['id'=>$request->request->get('user')]);
           foreach ($users as $user) {
            $userGroup->addUser($user);
           }
           $userGroup->setUpdatedAt(new \DateTime());
           $entityManager->flush();

       }
        return $this->render('user_group/add.user.html.twig', [
            'user_group' => $userGroup,
            'users' => $userRepository->findForUserGroup($userGroup->getUsers()),
           
        ]);
 

}

    #[Route('/{id}', name: 'app_user_group_show', methods: ['GET'])]
    public function show(UserGroup $userGroup): Response
    {
        return $this->render('user_group/show.html.twig', [
            'user_group' => $userGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserGroup $userGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_group/edit.html.twig', [
            'user_group' => $userGroup,
            'form' => $form,
        ]);
    }

    //        /**
    //  * @Route("/{id}/permission", name="user_group_permission", methods={"GET","POST"})
    //  */

    #[Route('/{id}/permission', name: 'user_group_permission', methods: ['GET','POST'])]

    public function permission(UserGroup $userGroup,Request $request,PermissionRepository $permissionRepository, EntityManagerInterface $entityManager){
        
        // $this->denyAccessUnlessGranted('ad_prmsn_to_grp');
 
        if($request->request->get('usergrouppermission') ){
        // dd($request->request->get('usergrouppermission'));

        //    $permissions=$permissionRepository->findAll();
           $permissions=$permissionRepository->findBy(['id'=>$request->request->get('permission')]);

        //    dd($permissions);
              foreach ($permissions as $permission) {
            $userGroup->removePermission($permission);
           }

           $permissions=$permissionRepository->findBy(['id'=>$request->request->get('permission')]);
           foreach ($permissions as $permission) {
             //   
            $userGroup->addPermission($permission);
           }
         
        //    $userGroup->setUpdatedAt(new \DateTime());
        //    $userGroup->setUpdatedBy($this->getUser());
           $entityManager->flush();
       }
        return $this->render('user_group/show.html.twig', [
            'user_group' => $userGroup,
            'permissions' => $permissionRepository->findForUserGroup($userGroup->getPermission()),
           
        ]);
 

}

    #[Route('/{id}', name: 'app_user_group_delete', methods: ['POST'])]
    public function delete(Request $request, UserGroup $userGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userGroup->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
