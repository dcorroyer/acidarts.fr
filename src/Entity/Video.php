<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Video
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
    private $videoname;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="videos", cascade={"persist"})
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

    public function setVideoname(?string $videoname): self
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
