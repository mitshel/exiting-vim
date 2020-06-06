<?php


namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="adjectives_morf")
 */
class AdjectivesMorf
{
    /**
     * @ORM\Column(name="iid", type="integer")
     * @ORM\Id()
     */
    public $iid;

    /**
     * @ORM\Column(name="word", type="string")
     */
    public $word;

    /**
     * @ORM\Column(name="code", type="integer")
     */
    public $code;

    /**
     * @ORM\Column(name="code_parent", type="integer")
     */
    public $codeParent;

    /**
     * @ORM\Column(name="type_sub", type="string")
     */
    public $typeSub;

    /**
     * @ORM\Column(name="plural", type="integer")
     */
    public $plural;

    /**
     * @ORM\Column(name="gender", type="string")
     */
    public $gender;

    /**
     * @ORM\Column(name="wcase", type="string")
     */
    public $wcase;

    /**
     * @ORM\Column(name="comp", type="string")
     */
    public $comp;

    /**
     * @ORM\Column(name="short", type="integer")
     */
    public $short;

    /**
     * @return mixed
     */
    public function getIid()
    {
        return $this->iid;
    }

    /**
     * @param mixed $iid
     */
    public function setIid($iid): void
    {
        $this->iid = $iid;
    }

    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word): void
    {
        $this->word = $word;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCodeParent()
    {
        return $this->codeParent;
    }

    /**
     * @param mixed $codeParent
     */
    public function setCodeParent($codeParent): void
    {
        $this->codeParent = $codeParent;
    }

    /**
     * @return mixed
     */
    public function getTypeSub()
    {
        return $this->typeSub;
    }

    /**
     * @param mixed $typeSub
     */
    public function setTypeSub($typeSub): void
    {
        $this->typeSub = $typeSub;
    }

    /**
     * @return mixed
     */
    public function getPlural()
    {
        return $this->plural;
    }

    /**
     * @param mixed $plural
     */
    public function setPlural($plural): void
    {
        $this->plural = $plural;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getWcase()
    {
        return $this->wcase;
    }

    /**
     * @param mixed $wcase
     */
    public function setWcase($wcase): void
    {
        $this->wcase = $wcase;
    }

    /**
     * @return mixed
     */
    public function getComp()
    {
        return $this->comp;
    }

    /**
     * @param mixed $comp
     */
    public function setComp($comp): void
    {
        $this->comp = $comp;
    }

    /**
     * @return mixed
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param mixed $short
     */
    public function setShort($short): void
    {
        $this->short = $short;
    }

}