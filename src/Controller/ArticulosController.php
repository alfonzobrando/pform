<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\Comentarios;
use App\Form\ArticulosType;
use App\Form\ComentariosType;
use App\Repository\ArticulosRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

##[Route('/articulos')]
class ArticulosController extends AbstractController
{
    #[Route('/', name: 'app_articulos_index', methods: ['GET'])]
    public function listarArticulos(ArticulosRepository $articulosRepository): Response
    {
        return $this->render('articulos/index.html.twig', [
            'articulos' => $articulosRepository->findAll(),
        ]);
    }

    #[Route('/articulos/new', name: 'app_articulos_new', methods: ['GET', 'POST'])]
    public function crearArticulo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articulo = new Articulos();
        $articulo->setCreado(new DateTimeImmutable());
        $form = $this->createForm(ArticulosType::class, $articulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($articulo);
            $entityManager->flush();

            return $this->redirectToRoute('app_articulos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articulos/new.html.twig', [
            'articulo' => $articulo,
            'form' => $form,
        ]);
    }

    #[Route('/articulos/{id}', name: 'app_articulos_show', methods: ['GET'])]
    public function mostrarArticulo(Request $request, Articulos $articulo, EntityManagerInterface $entityManager): Response
    {
        $comentarios = $articulo->getComments();
        return $this->render('articulos/show.html.twig', [
            'articulo' => $articulo,
            'comentarios' => $comentarios
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articulos_edit', methods: ['GET', 'POST'])]
    public function editarArticuloById(Request $request, Articulos $articulo, EntityManagerInterface $entityManager): Response
    {
        /*$form = $this->createForm(ArticulosType::class, $articulo);
        $form->handleRequest($request);*/

        $form = $this->createFormBuilder($articulo)
                ->add('titulo')
                ->add('autor')
                ->add('contenido')
                ->add('categoria')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_articulos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articulos/edit.html.twig', [
            'articulo' => $articulo,
            'form' => $form,
        ]);
    }

    #[Route('articulos/{id}', name: 'app_articulos_delete', methods: ['POST'])]
    public function borrarArticulo(Request $request, Articulos $articulo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articulo->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($articulo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articulos_index', [], Response::HTTP_SEE_OTHER);
    }
}
