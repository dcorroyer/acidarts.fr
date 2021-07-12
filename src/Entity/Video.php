<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Url()
     * @ORM\Column(type="string", length=255)
     */
    private $videoname;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoname(): ?string
    {
        return $this->videoname;
    }

    public function setVideoname(string $videoname): self
    {
        $this->videoname = $videoname;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
