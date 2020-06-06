<?php


namespace Core\Controller;


use Core\Entity\Instruction;
use Core\Entity\Participles;
use Core\Entity\Words;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlovaController extends AbstractController
{
    /**
     * @Route("/slova", name="slova")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $items = $this->getDoctrine()->getRepository(Instruction::class)->findBy([
            'item' => 2
        ]);

        /** @var Instruction $item */
        foreach ($items as $item) {
            $str = $item->getItem()->getName();

            // Убираем порядковые номера разделов
            $str = preg_replace('/([^а-яА-Яa-zA-Z\s\-\,\.\:])/u', '', $str);

            // Убираем все что в скобке
            $str = preg_replace('/\(.*?\)/u', '', $str);

            // Убираем лишние точки
            $str = preg_replace('/\.\s{2}./u', '.', $str);

            $keywords = preg_split("/[\s,]+/", $str);

            $i = 0;
            while ($i < count($keywords)) {
                if (mb_strlen($keywords[$i]) > 2) {
                    echo($keywords[$i]);
                    /** @var Participles $prich */
                    $prich = $this->getDoctrine()->getRepository(Participles::class)->word($keywords[$i]);
                    if ($prich) {
                        $pos = strpos($str, $prich->getWord());
                        $posZ = strpos(substr($str, $pos), ',');
                        $str = str_replace(substr($str, $pos, $posZ), '', $str);
                        $keywords = preg_split("/[\s,]+/", $str);

                        $i = 0;
                        break;
                    }
                }
                $i++;
            }

            print_r('<br>');
            print_r('<br>');
            print_r($str);
        }

//        /** @var Words $slovo */
//        $slova = $this->getDoctrine()->getRepository(Participles::class)->word('компьютер');
//
//        foreach ($slova as $slovo) {
//            dump($slovo->getWord());
//        }


        return $this->render('index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    private function particitles($keywords)
    {

    }
}