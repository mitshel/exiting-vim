<?php

namespace Core\Controller;

use Core\Entity\Instruction;
use Core\Entity\InstructionContent;
use Core\Entity\Post;
use Core\Entity\Section;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/archive", name="archive_")
 */
class ArchiveController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function base()
    {
        $map = [
            'a' => 'А',
            'b' => 'Б',
            'v' => 'В',
            'g' => 'Г',
            'd' => 'Д',
            'e' => 'Е',
            'j' => 'Ж',
            'z' => 'З',
            'i' => 'И',
            'k' => 'К',
            'l' => 'Л',
            'm' => 'М',
            'n' => 'Н',
            'o' => 'О',
            'p' => 'П',
            'r' => 'Р',
            's' => 'С',
            'y' => 'Т',
            'u' => 'У',
            'f' => 'Ф',
            'x' => 'Х',
            'c' => 'Ц',
            'ch' => 'Ч',
            'sh' => 'Ш',
            'sch' => 'Щ',
            'ie' => 'Э',
            'yu' => 'Ю',
            'ya' => 'Я',
        ];

        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)->findBy([], ['name' => 'ASC']);

        $data = [];

        foreach ($map as $k => $v) {
            $data[$k] = [
                'letter' => $v,
                'childs' => [],
            ];
        }

        /** @var Post $post */
        foreach ($posts as $post) {
            $first = mb_substr($post->getName(), 0, 1, "UTF-8");
            if (in_array($first, $map)) {
                $k = array_search($first, $map);

                $data[$k]['childs'][] = [
                    'id' => $post->getId(),
                    'name' => $post->getName(),
                ];
            }
        }

        return $this->render('archive/index.html.twig', [
            'data' => $data,
        ]);
    }


    /**
     * @Route("/{id}/show",  name="spec")
     */
    public function indexSpec(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $instruction = $this->getDoctrine()->getRepository(Instruction::class)->findOneBy(['post' => $post]);

        $mainSection = $em->getRepository(Section::class)->find(1);
        $obyazSection = $em->getRepository(Section::class)->find(2);
        $pravaSection = $em->getRepository(Section::class)->find(3);
        $otvetSection = $em->getRepository(Section::class)->find(4);

        $data = [];

        $data['post'] = $post;

        $inst = $em->getRepository(InstructionContent::class)->findBy([
            'instruction' => $instruction,
            'section' => $mainSection,
        ], ['id' => 'asc']);

        $data['main'] = [];
        /** @var InstructionContent $item */
        foreach ($inst as $item) {
            $data['main'][] = $item->getItem()->getName();
        }

        $inst = $em->getRepository(InstructionContent::class)->findBy([
            'instruction' => $instruction,
            'section' => $obyazSection,
        ], ['id' => 'asc']);

        $data['obyaz'] = [];
        /** @var InstructionContent $item */
        foreach ($inst as $item) {
            $data['obyaz'][] = $item->getItem()->getName();
        }

        $inst = $em->getRepository(InstructionContent::class)->findBy([
            'instruction' => $instruction,
            'section' => $pravaSection,
        ], ['id' => 'asc']);

        $data['prava'] = [];
        /** @var InstructionContent $item */
        foreach ($inst as $item) {
            $data['prava'][] = $item->getItem()->getName();
        }

        $inst = $em->getRepository(InstructionContent::class)->findBy([
            'instruction' => $instruction,
            'section' => $otvetSection,
        ], ['id' => 'asc']);

        $data['otvet'] = [];
        /** @var InstructionContent $item */
        foreach ($inst as $item) {
            $data['otvet'][] = $item->getItem()->getName();
        }

        return $this->render('spec.html.twig', $data);
    }
}