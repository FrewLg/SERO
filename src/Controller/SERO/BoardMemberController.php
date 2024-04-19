<?php

namespace App\Controller\SERO;

use App\Entity\SERO\BoardMember;
use App\Form\SERO\BoardMemberType;
use App\Repository\SERO\BoardMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Utils\Constants;


#[Route('{_locale<%app.supported_locales%>}/board-member')]
class BoardMemberController extends AbstractController
{
    #[Route('/', name: 'board_member_index',  methods: ['GET', "POST"])]
    public function index(Request $request , EntityManagerInterface $entityManager, BoardMemberRepository $boardMemberRepository, PaginatorInterface $paginator): Response
    {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // dd($request);
            $boardMember = new BoardMember();
            $form = $this->createForm(BoardMemberType::class, $boardMember);
    
            $form->handleRequest($request);
    
            if ($request->request->get('change-role')) {
    
    
    
                $boardMember = $boardMemberRepository->find($request->request->get('board_member'));
                $boardMember->setRole($request->request->get('roles'));
                $boardMember->getUser()->addRole(Constants::ROLE_BOARD_MEMBER);
                  
                $entityManager->flush();
                $this->addFlash("success", "Role changed");
    
                return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
            }
    
    
            if ($form->isSubmitted() && $form->isValid()) {
    
                if ($this->getUser()->getProfile()) {
                    $boardMember->setAssignedBy($this->getUser());
                    $boardMember->getUser()->addRole(Constants::ROLE_BOARD_MEMBER);
                    $boardMember->getUser()->addRole(
                        // $form['role']->getData()
                         $form->get('role')->getData());
                    // $boardMember->setDirectorate($this->getUser()->getProfile()->getDirectorate());
              
                    $entityManager->persist($boardMember);
                    
                    $entityManager->flush();
                    $this->addFlash("success", "Registered successfully");
                } else {
                    $this->addFlash("danger", "Your college is not set");
                }
    
                return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
            }

            $queryBulder = $boardMemberRepository->getData(["search" => $request->query->get('search')]);
            $board_members = $paginator->paginate(
                $queryBulder,
                $request->query->getInt('page', 1),
                10
            );
    
            return $this->render('sero/board_member/index.html.twig', [ 
                'board_members' => $board_members,
                'board_member' => $boardMember,
                'form' => $form->createView(),
            ]);
        }


    #[Route('/new', name: 'app_s_e_r_o_board_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $boardMember = new BoardMember();
        $form = $this->createForm(BoardMemberType::class, $boardMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($boardMember);
            $entityManager->flush();

            return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/board_member/new.html.twig', [
            'board_member' => $boardMember,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_board_member_show', methods: ['GET'])]
    public function show(BoardMember $boardMember): Response
    {
        return $this->render('sero/board_member/show.html.twig', [
            'board_member' => $boardMember,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_board_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BoardMember $boardMember, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BoardMemberType::class, $boardMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/board_member/edit.html.twig', [
            'board_member' => $boardMember,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_board_member_delete', methods: ['POST'])]
    public function delete(Request $request, BoardMember $boardMember, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boardMember->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($boardMember);
            $entityManager->flush();
        }

        return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
