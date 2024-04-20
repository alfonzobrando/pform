<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\Comentario;
use App\Form\ComentarioType;
use App\Repository\ComentarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{idArticulo}/comentario')]
class ComentarioController extends AbstractController
{
    #[Route('/', name: 'app_comentario_index', methods: ['GET'])]
    public function index(ComentarioRepository $comentarioRepository): Response
    {
        return $this->render('comentario/index.html.twig', [
            'comentarios' => $comentarioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comentario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Articulos $idArticulo,  EntityManagerInterface $entityManager): Response
    {
        $comentario = new Comentario();
        $comentario->setArticulos($idArticulo);
        $form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comentario);
            $entityManager->flush();

            return $this->redirectToRoute('app_articulos_show', [
                'id' => $idArticulo->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comentario/new.html.twig', [
            'comentario' => $comentario,
            'form' => $form,
            'idArticulo' => $idArticulo->getId()
        ]);
    }

    #[Route('/{id}', name: 'app_comentario_show', methods: ['GET'])]
    public function show(Comentario $comentario): Response
    {
        return $this->render('comentario/show.html.twig', [
            'comentario' => $comentario,
        ]);
    }

    #[Route('/{idComentario}/edit', name: 'app_comentario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articulos $idArticulo ,Comentario $idComentario, EntityManagerInterface $entityManager): Response
    {
        /*$form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);*/

        $form = $this->createFormBuilder($idComentario)
                ->add('contenido')
                ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_articulos_show', [
                'id' => $idArticulo->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comentario/edit.html.twig', [
            'comentario' => $idComentario,
            'form' => $form,
            'articulo' => $idArticulo
        ]);
    }

    #[Route('/{id}', name: 'app_comentario_delete', methods: ['POST'])]
    public function delete(Request $request, Articulos $idArticulo, Comentario $comentario, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comentario->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($comentario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articulos_show', ['id' => $idArticulo->getId()] );
    }
}
