<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SuperHeroRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SuperHeroRepository::class)]
#[Vich\Uploadable()]
class SuperHero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alterEgo = null;

    #[ORM\Column]
    private ?bool $estDisponible = null;

    #[ORM\Column]
    private ?int $niveauEnergie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biographie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomImage = null;

    #[Vich\UploadableField(mapping: 'super_heros', fileNameProperty: 'nomImage')]
    #[Assert\Image(
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez téléverser une image valide (png, jpeg, jpg)'
    )]
    private ?File $imageFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, SuperPouvoir>
     */
    #[ORM\ManyToMany(targetEntity: SuperPouvoir::class, inversedBy: 'superHeroes')]
    private Collection $Pouvoir;

    /**
     * @var Collection<int, Equipe>
     */
    #[ORM\OneToMany(targetEntity: Equipe::class, mappedBy: 'chef')]
    private Collection $equipesGerees;

    /**
     * @var Collection<int, Equipe>
     */
    #[ORM\ManyToMany(targetEntity: Equipe::class, mappedBy: 'membres')]
    private Collection $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
        $this->Pouvoir = new ArrayCollection();
        $this->equipesGerees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAlterEgo(): ?string
    {
        return $this->alterEgo;
    }

    public function setAlterEgo(?string $alterEgo): static
    {
        $this->alterEgo = $alterEgo;

        return $this;
    }

    public function isEstDisponible(): ?bool
    {
        return $this->estDisponible;
    }

    public function setEstDisponible(bool $estDisponible): static
    {
        $this->estDisponible = $estDisponible;

        return $this;
    }

    public function getNiveauEnergie(): ?int
    {
        return $this->niveauEnergie;
    }

    public function setNiveauEnergie(int $niveauEnergie): static
    {
        $this->niveauEnergie = $niveauEnergie;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }

    public function getNomImage(): ?string
    {
        return $this->nomImage;
    }

    public function setNomImage(?string $nomImage): static
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, SuperPouvoir>
     */
    public function getPouvoir(): Collection
    {
        return $this->Pouvoir;
    }

    public function addPouvoir(SuperPouvoir $pouvoir): static
    {
        if (!$this->Pouvoir->contains($pouvoir)) {
            $this->Pouvoir->add($pouvoir);
        }

        return $this;
    }

    public function removePouvoir(SuperPouvoir $pouvoir): static
    {
        $this->Pouvoir->removeElement($pouvoir);

        return $this;
    }

    /**
     * Get the value of imageFile
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipesGerees(): Collection
    {
        return $this->equipesGerees;
    }

    public function addEquipesGeree(Equipe $equipesGeree): static
    {
        if (!$this->equipesGerees->contains($equipesGeree)) {
            $this->equipesGerees->add($equipesGeree);
            $equipesGeree->setChef($this);
        }

        return $this;
    }

    public function removeEquipesGeree(Equipe $equipesGeree): static
    {
        if ($this->equipesGerees->removeElement($equipesGeree)) {
            // set the owning side to null (unless already changed)
            if ($equipesGeree->getChef() === $this) {
                $equipesGeree->setChef(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): static
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes->add($equipe);
            $equipe->addMembre($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): static
    {
        if ($this->equipes->removeElement($equipe)) {
            $equipe->removeMembre($this);
        }

        return $this;
    }
}
