<?php


namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Core\Repository\WordsRepository")
 * @ORM\Table(name="words")
 */
class Words
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="iid", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $iid;

    /**
     * @ORM\Column(name="word", type="string")
     *
     * @var string
     */
    private string $word;

    /**
     * @ORM\Column(name="code", type="string")
     *
     * @var int
     */
    private int $code;

    /**
     * @ORM\Column(name="code_parent", type="string")
     *
     * @var int
     */
    private int $code_parent;

    /**
     * @ORM\Column(name="type", type="string")
     *
     * @var string
     */
    private string $type;

    /**
     * @return int
     */
    public function getIid()
    {
        return $this->iid;
    }

    /**
     * @param int $iid
     */
    public function setIid($iid)
    {
        $this->iid = $iid;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getCodeParent()
    {
        return $this->code_parent;
    }

    /**
     * @param int $code_parent
     */
    public function setCodeParent($code_parent)
    {
        $this->code_parent = $code_parent;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}