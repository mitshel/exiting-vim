<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Core\Repository\InstructionContentRepository")
 * @ORM\Table(name="instructions_content")
 */
class InstructionContent
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue()
     * @ORM\Id
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Instruction",cascade={"persist"})
     * @ORM\JoinColumn(name="id_instruction", referencedColumnName="id")
     */
    private Instruction $instruction;

    /**
     * @ORM\ManyToOne(targetEntity="Item",cascade={"persist"})
     * @ORM\JoinColumn(name="id_item", referencedColumnName="id")
     */
    private Item $item;

    /**
     * @ORM\ManyToOne(targetEntity="Section",cascade={"persist"})
     * @ORM\JoinColumn(name="id_section", referencedColumnName="id")
     */
    private Section $section;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInstruction(): Instruction
    {
        return $this->instruction;
    }

    public function setInstruction(Instruction $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getSection(): Section
    {
        return $this->section;
    }

    public function setSection(Section $section): self
    {
        $this->section = $section;

        return $this;
    }


}