<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $estActif = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'equipesGerees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SuperHero $chef = null;

    /**
     * @var Collection<int, SuperHero>
     */
    #[ORM\ManyToMany(targetEntity: SuperHero::class, inversedBy: 'equipes')]
    private Collection $membres;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\OneToMany(targetEntity: Mission::class, mappedBy: 'equipe')]
    private Collection $missions;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Mission $missionEnCours = null;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->missions = new ArrayCollection();
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

    public function isEstActif(): ?bool
    {
        return $this->estActif;
    }

    public function setEstActif(bool $estActif): static
    {
        $this->estActif = $estActif;

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

    public function getChef(): ?SuperHero
    {
        return $this->chef;
    }

    public function setChef(?SuperHero $chef): static
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * @return Collection<int, SuperHero>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(SuperHero $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
        }

        return $this;
    }

    public function removeMembre(SuperHero $membre): static
    {
        $this->membres->removeElement($membre);

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setEquipe($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getEquipe() === $this) {
                $mission->setEquipe(null);
            }
        }

        return $this;
    }

    public function getMissionEnCours(): ?Mission
    {
        return $this->missionEnCours;
    }

    public function setMissionEnCours(?Mission $missionEnCours): static
    {
        $this->missionEnCours = $missionEnCours;

        return $this;
    }
}
