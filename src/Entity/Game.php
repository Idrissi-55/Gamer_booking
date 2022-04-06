<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $Starting_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $ending_date;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $winner;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $defeated;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'games')]
    /*#[JoinTable(name: "game_user")]
    #[JoinColumn(name: "user_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "game", referencedColumnName: "id")]*/
    private $Players;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'Games')]
    #[ORM\JoinColumn(nullable: false)]
    private $tournament;

    public function __construct()
    {
        $this->Players = new ArrayCollection();
    }

    

    

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->Starting_date;
    }

    public function setStartingDate(?\DateTimeInterface $Starting_date): self
    {
        $this->Starting_date = $Starting_date;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->ending_date;
    }

    public function setEndingDate(?\DateTimeInterface $ending_date): self
    {
        $this->ending_date = $ending_date;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(?string $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getDefeated(): ?string
    {
        return $this->defeated;
    }

    public function setDefeated(?string $defeated): self
    {
        $this->defeated = $defeated;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPlayers(): Collection
    {
        return $this->Players;
    }

    public function addPlayer(User $player): self
    {
        if (!$this->Players->contains($player)) {
            $this->Players[] = $player;
        }

        return $this;
    }

    public function removePlayer(User $player): self
    {
        $this->Players->removeElement($player);

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    
}
