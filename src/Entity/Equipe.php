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

    #[ORM\ManyToOne(inversedBy: 'equipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SuperHero $chef = null;

    /**
     * @var Collection<int, SuperHero>
     */
    #[ORM\ManyToMany(targetEntity: SuperHero::class, inversedBy: 'equipes')]
    private Collection $menbers;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\OneToMany(targetEntity: Mission::class, mappedBy: 'equipeEnCharge')]
    private Collection $missions;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Mission $missionEnCours = null;

    public function __construct()
    {
        $this->menbers = new ArrayCollection();
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
    public function getMenbers(): Collection
    {
        return $this->menbers;
    }

    public function addMenber(SuperHero $menber): static
    {
        if (!$this->menbers->contains($menber)) {
            $this->menbers->add($menber);
        }

        return $this;
    }

    public function removeMenber(SuperHero $menber): static
    {
        $this->menbers->removeElement($menber);

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
            $mission->setEquipeEnCharge($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getEquipeEnCharge() === $this) {
                $mission->setEquipeEnCharge(null);
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
