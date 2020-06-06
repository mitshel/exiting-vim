<?php

namespace Core\Command;

use Core\Entity\Item;
use Core\Entity\ItemWord;
use Core\Entity\Participles;
use Core\Entity\Word;
use Core\Entity\Words;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SchetCommand extends Command
{
    protected static $defaultName = 'word:run';

    protected ObjectManager $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start',
            '============',
            '',
        ]);

        $items = $this->em->getRepository(Item::class)->findAll();

        /** @var Item $item */
        foreach ($items as $item) {
            $name = str_replace('—', ' ', $item->getName());
            $name = preg_replace ('/^[0-9:;.\-\'\"]+|[.,:;()\-_;\'\"\t\s]+/i', ' ', $name);
            $name = trim(mb_strtoupper($name));

            foreach (explode( ' ', $name) as $w) {

                if (empty($w)) {
                    continue;
                }

                /** @var Words $word */
                $word = $this->em->getRepository(Words::class)->wordOne($w);

                while ($word instanceof Words && $word->getWCase() !== 'им' && $word->getWCase() !== null) {
                    $word = $this->em->getRepository(Words::class)->findOneBy(['code' => $word->getCodeParent()]);
                }

                if ($word instanceof Words) {
                    $wordItem = $this->em->getRepository(ItemWord::class)->findOneBy(['word' => $word, 'item' => $item]) ?? new ItemWord();
                    $wordItem->setWord($word);
                    $wordItem->setValue($w);
                    $cnt = $wordItem->getCnt();
                    $wordItem->setCnt(++$cnt);
                    $wordItem->setItem($item);
                } else {
                    $wordItem = $this->em->getRepository(ItemWord::class)->findOneBy(['value' => $w, 'item' => $item]) ?? new ItemWord();
                    $wordItem->setWord(null);
                    $wordItem->setValue($w);
                    $cnt = $wordItem->getCnt();
                    $wordItem->setCnt(++$cnt);
                    $wordItem->setItem($item);
                }

                $this->em->persist($wordItem);
                $this->em->flush();
            }
        }

        return 0;
    }
}