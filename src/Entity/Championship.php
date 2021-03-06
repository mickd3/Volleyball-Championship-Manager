<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineChampionshipRepository")
 */
class Championship
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $began;

    /**
     * @ORM\Embedded(class = "SpecificationPoint")
     */
    private $specificationPoint;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pool", mappedBy="championship", orphanRemoval=true)
     */
    private $pools;


    public function __construct(string $name, bool $began, SpecificationPoint $specificationPoint)
    {
        $this->name = $name;
        $this->began = $began;
        $this->specificationPoint = $specificationPoint;

        $this->pools = new ArrayCollection();
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function updateSpecificationPoint( SpecificationPoint $specificationPoint ): void
    {
        $this->specificationPoint = $specificationPoint;
    }

    public function start(): void
    {
        $this->began = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBegan(): ?bool
    {
        return $this->began;
    }

    public function getSpecificationPoint(): ?SpecificationPoint
    {
        return $this->specificationPoint;
    }

    /**
     * @return Collection|Pool[]
     */
    public function getPools(): Collection
    {
        return $this->pools;
    }

    public function addPool(Pool $pool): self
    {
        if (!$this->pools->contains($pool)) {
            $this->pools[] = $pool;
            $pool->setChampionship($this);
        }

        return $this;
    }

    public function removePool(Pool $pool): self
    {
        if ($this->pools->contains($pool)) {
            $this->pools->removeElement($pool);
            // set the owning side to null (unless already changed)
            if ($pool->getChampionship() === $this) {
                $pool->setChampionship(null);
            }
        }

        return $this;
    }
}
