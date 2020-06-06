<?php


namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="verbs")
 */
class Verbs
{
    /**
     * @var int
     *
     *  @ORM\Id()
     * @ORM\Column(name="iid", type="integer")
     */
    private int $iid;

    /**
     * @var string
     *
     * @ORM\Column(name="verbs", type="string")
     */
    private string $word;

    /**
     * @var int
     *
     * @ORM\Column(name="code")
     */
    private int $code;

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
    }
}