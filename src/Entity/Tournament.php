<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\GreaterThan('today', message : 'La date de début doit être postérieure à aujourd\'hui')]
    private $starting_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Expression('this.getStartingDate() < this.getEndingDate()', message: 'La date de fin doit être postérieure à la date de début')]
    /*#[Assert\Expression('value + error_margin < threshold', values: ['error_margin' => 0.25, 'threshold' => 1.5] )]*/
    private $ending_date;

    #[ORM\Column(type: 'float')]
    #[Assert\Range(
        min: '0.1',
        max: '1000',
    )]
    private $Award;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: Game::class)]
    private $Games;

    #[ORM\Column(type: 'text', nullable: true)]
    private $Description;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private $nbPlayers = 0;

    #[ORM\Column(type: 'array', nullable: true)]
    private $Pairs = [];

    public function __construct()
    {
        $this->Games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->starting_date;
    }

    public function setStartingDate(?\DateTimeInterface $starting_date): self
    {
        $this->starting_date = $starting_date;

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

    public function getAward(): ?float
    {
        return $this->Award;
    }

    public function setAward(float $Award): self
    {
        $this->Award = $Award;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->Games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->Games->contains($game)) {
            $this->Games[] = $game;
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->Games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNbPlayers(): ?int
    {
        return $this->nbPlayers;
    }

    public function setNbPlayers(?int $nbPlayers): self
    {
        $this->nbPlayers = $nbPlayers;

        return $this;
    }

    public function getPairs(): ?array
    {
        return $this->Pairs;
    }

    public function setPairs(?array $Pairs): self
    {
        $this->Pairs = $Pairs;

        return $this;
    }

    public function addPlayer($id) {
        
        array_push($this->Pairs, $id);
    }

}
