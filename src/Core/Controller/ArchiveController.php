<?php

namespace Core\Controller;

use Core\Entity\Post;
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
            'a' => 'A',
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
            'data' => $data
        ]);
    }
}