<?php

namespace App\Entity;

use App\Repository\ArticulosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticulosRepository::class)]
class Articulos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $autor = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenido = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creado = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'articulos', orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
       $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getCreado(): ?\DateTimeInterface
    {
        return $this->creado;
    }

    public function setCreado(\DateTimeInterface $creado): static
    {
        $this->creado = $creado;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

   /**
    * @return Collection<int, Comentario>
    */
   public function getComments(): Collection
   {
       return $this->comments;
   }

   public function addComment(Comentario $comment): static
   {
       if (!$this->comments->contains($comment)) {
           $this->comments->add($comment);
           $comment->setArticulos($this);
       }

       return $this;
   }

   public function removeComment(Comentario $comment): static
   {
       if ($this->comments->removeElement($comment)) {
           // set the owning side to null (unless already changed)
           if ($comment->getArticulos() === $this) {
               $comment->setArticulos(null);
           }
       }

       return $this;
   }
}
