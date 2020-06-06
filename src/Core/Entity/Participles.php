<?php


namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Core\Repository\ParticiplesRepository")
 * @ORM\Table(name="participles_morf")
 */
class Participles
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="iid", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private int $iid;

    /**
     * @ORM\Column(name="word", type="string")
     *
     * @var string
     */
    private string $word;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="integer")
     */
    private string $code;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private string $type;

    /**
     * @var string
     *
     * @ORM\Column(name="wcase", type="string")
     */
    private string $wCase;

    /**
     * @var string
     *
     * @ORM\Column(name="code_parent", type="string")
     */
    private string $codeParent;

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
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
    }

    /**
     * @return string
     */
    public function getWCase()
    {
        return $this->wCase;
    }

    /**
     * @param string $wCase
     */
    public function setWCase($wCase)
    {
        $this->wCase = $wCase;
    }

    /**
     * @return string
     */
    public function getCodeParent()
    {
        return $this->codeParent;
    }

    /**
     * @param string $codeParent
     */
    public function setCodeParent($codeParent)
    {
        $this->codeParent = $codeParent;
    }
}