<?php


namespace Core\Command;


use Core\Entity\Instruction;
use Core\Entity\InstructionContent;
use Core\Entity\NewText1;
use Core\Entity\Participles;
use Core\Entity\Section;
use Core\Entity\Verbs;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewTextCommand extends Command
{
    protected static $defaultName = 'newtext:run';

    protected ObjectManager $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start',
            '============',
            '',
        ]);

        $instructions = $this->em->getRepository(Instruction::class)->findAll();

        /** @var Section $obyazSection */
        $obyazSection = $this->em->getRepository(Section::class)->find(2);
        /** @var Section $obyazSection */
        $pravaSection = $this->em->getRepository(Section::class)->find(3);
        /** @var Section $obyazSection */
        $otvetSection = $this->em->getRepository(Section::class)->find(4);


        foreach ($instructions as $instruction) {
            $inst = $this->em->getRepository(InstructionContent::class)->findBy([
                'instruction' => $instruction,
                'section' => $obyazSection,
            ], ['id' => 'asc']);

            $obyaz = [];
            /** @var InstructionContent $item */
            foreach ($inst as $i) {
                $obyaz[] = $i->getItem()->getName();
            }
            $strObyaz = $this->str(implode('. ', $obyaz));

            $newtext = new NewText1();
            $newtext->setSection($obyazSection);
            $newtext->setIntstr($instruction);
            $newtext->setText($strObyaz);
            $this->em->persist($newtext);
            $this->em->flush();


            $inst = $this->em->getRepository(InstructionContent::class)->findBy([
                'instruction' => $instruction,
                'section' => $pravaSection,
            ], ['id' => 'asc']);

            $prava = [];
            /** @var InstructionContent $item */
            foreach ($inst as $item) {
                $prava[] = $item->getItem()->getName();
            }
            $strObyaz = $this->str(implode('. ', $prava));

            $newtext = new NewText1();
            $newtext->setSection($pravaSection);
            $newtext->setIntstr($instruction);
            $newtext->setText($strObyaz);
            $this->em->persist($newtext);
            $this->em->flush();


            $inst = $this->em->getRepository(InstructionContent::class)->findBy([
                'instruction' => $instruction,
                'section' => $otvetSection,
            ], ['id' => 'asc']);

            $otvet = [];
            /** @var InstructionContent $item */
            foreach ($inst as $item) {
                $otvet[] = $item->getItem()->getName();
            }

            $strOtvet = $this->str(implode('. ', $otvet));
            $newtext = new NewText1();
            $newtext->setSection($otvetSection);
            $newtext->setIntstr($instruction);
            $newtext->setText($strOtvet);
            $this->em->persist($newtext);
            $this->em->flush();
        }
        return 0;
    }

    private function str($str)
    {
        $str = $this->regex($str);

        $keywords = preg_split("/[\s,]+/", $str);
        $i = 0;
        while ($i < count($keywords)) {
            if (mb_strlen($keywords[$i]) > 2) {
                /** @var Participles $prich */
                $prich = $this->em->getRepository(Participles::class)->word($keywords[$i]);
                if (
                    $prich &&
                    iconv_substr($keywords[$i], 0, strlen($keywords[$i]) - 2) ==
                    iconv_substr($prich->getWord(), 0, strlen($keywords[$i]) - 2)
                ) {
                    $pos = iconv_strpos($str, $keywords[$i]);
                    $posZ =  iconv_strpos(iconv_substr($str, $pos), ',');
                    $posZ2 = iconv_strpos(iconv_substr($str, $pos), '.');
                    $str = str_replace(iconv_substr($str, $pos, min($posZ, $posZ2)), ' ', $str);
                    $str = $this->regex($str);
                    $keywords = preg_split("/[\s,]+/", $str);
                }
            }
            $i++;
        }

        $str = $this->regex($str);
        $str = $this->pred($str);

        return $str;
    }

    private function pred($str)
    {
        $pred = explode('.', $str);

        foreach ($pred as &$item) {
            $slova = preg_split("/[\s,]+/", $item);
            $i = 0;
            foreach ($slova as &$slv) {
                $i++;
                if (empty($slv)) {
                    unset($slv);
                    $i = 0;
                    continue;
                }

                if ($i == 1) {
                    $verb = $this->em->getRepository(Verbs::class)->word2($slv);
                    if (!$verb) {
                        foreach ($slova as $k => &$slv2) {
                            $verb2 = $this->em->getRepository(Verbs::class)->word2($slv2);
                            if (!$verb2) {
                                //dump($slv2);
                                unset($slova[$k]);
                            } else {
                                $slova[$k] = ' ' . $slova[$k];
                                break;
                            }
                        }
                    }
                }
            }
            $item = implode(' ', $slova);
        }

        return implode('.', $pred);
    }

    private function regex($str)
    {
        // Убираем порядковые номера разделов
        $str = preg_replace('/([^а-яА-ЯЁёЪъa-zA-Z\s\-\,\.\:])/u', '', $str);

        // Убираем все что в скобке
        $str = preg_replace('/\(.*?\)/u', '', $str);

        $str = preg_replace('/\.\s{2,}./u', '.', $str);

        $str = preg_replace('/(Российская Федерация)|(Российской Федерации)|(Российскую Федерацию)/u', 'РФ', $str);

        $str = preg_replace('/\.{2}/u', '', $str);

        /** Убираем лишние запятые */
        $str = preg_replace('/,\s{0,},/u', ',', $str);

        if (isset ($str[0]) && ($str[0] == '.')) {
            $str = iconv_substr($str, 1);

        }

        $str = str_replace('. .', '.', $str);

        $str = preg_replace('/,\s{2,},/u', '', $str);
        $str = preg_replace('/,\s{2,}\./u', '.', $str);

        $str = trim($str);

        return $str;
    }


}