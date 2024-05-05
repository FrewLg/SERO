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
    public function index(Request $request, EntityManagerInterface $entityManager, 
    BoardMemberRepository $boardMemberRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $boardMember = new BoardMember();
        $form = $this->createForm(BoardMemberType::class, $boardMember);
        $form->handleRequest($request);
        if ($request->request->get('change-role')) {
            $boardMember = $boardMemberRepository->find($request->request->get('board_member'));
            $boardMember->setRole($request->request->get('roles'));
            $boardMember->getUser()->addRole(Constants::ROLE_BOARD_MEMBER);
            $entityManager->flush();
            $this->addFlash("success", "Role has been changed successfully!");
            return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($boardMemberRepository->findOneBy(['user' => $form->get('user')->getData()])) {
                $this->addFlash("danger", "The user is already board memeber. You might have wanted to change hes/her roles");
                return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
            }

            if ($this->getUser()->getProfile()) {
                $boardMember->setAssignedBy($this->getUser());
                $boardMember->getUser()->addRole(Constants::ROLE_BOARD_MEMBER);
                $boardMember->getUser()->addRole($form->get('role')->getData());
                $entityManager->persist($boardMember);
                $entityManager->flush();
                $this->addFlash("success", "Board member has been registered successfully");
                //send email notification
            } else {
                $this->addFlash("danger", "Update your profile please!");
                return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
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

    

    #[Route('/{id}', name: 'app_s_e_r_o_board_member_show', methods: ['GET'])]
    public function show(BoardMember $boardMember): Response
    {
        return $this->render('sero/board_member/show.html.twig', [
            'board_member' => $boardMember,
        ]);
    }
 

    #[Route('/{id}', name: 'app_s_e_r_o_board_member_delete', methods: ['POST'])]
    public function delete(Request $request, BoardMember $boardMember, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $boardMember->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($boardMember);
            $entityManager->flush();
        }

        return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
