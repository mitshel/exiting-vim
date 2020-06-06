<?php
namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Core\Repository\InstructionRepository")
 * @ORM\Table(name="instructions")
 */
class Instruction
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue()
     * @ORM\Id
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Post",cascade={"persist"})
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id")
     */
    private Post $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}