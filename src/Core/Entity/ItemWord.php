<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="item_word")
 */
class ItemWord
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue()
     * @ORM\Id
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Core\Entity\Words",cascade={"persist"})
     * @ORM\JoinColumn(name="id_word", referencedColumnName="iid")
     */
    private Words $word;

    /**
     * @ORM\ManyToOne(targetEntity="Item",cascade={"persist"})
     * @ORM\JoinColumn(name="id_item", referencedColumnName="id")
     */
    private Item $item;

    /**
     * @ORM\Column(name="cnt", type="integer")
     */
    private int $cnt = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getWord(): Words
    {
        return $this->word;
    }

    public function setWord(Words $word): self
    {
        $this->word = $word;

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

    public function getCnt(): int
    {
        return $this->cnt;
    }

    public function setCnt(int $cnt): self
    {
        $this->cnt = $cnt;
        return $this;
    }
}