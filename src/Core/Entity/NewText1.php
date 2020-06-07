<?php


namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="newtextold")
 */
class NewText1
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var Instruction
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Instruction")
     * @ORM\JoinColumn(name="id_instruction", referencedColumnName="id")
     */
    private $intstr;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="Core\Entity\Section")
     * @ORM\JoinColumn(name="section", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Instruction
     */
    public function getIntstr()
    {
        return $this->intstr;
    }

    /**
     * @param Instruction $intstr
     */
    public function setIntstr($intstr)
    {
        $this->intstr = $intstr;
    }

    /**
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}