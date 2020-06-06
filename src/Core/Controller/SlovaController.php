<?php


namespace Core\Controller;


use Core\Entity\Instruction;
use Core\Entity\Participles;
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

        $items = $this->getDoctrine()->getRepository(Instruction::class)->findArr(21);
        $arr = array_column($items, 'it');

        $str = implode(' ', $arr);

        // Убираем порядковые номера разделов
        $str = preg_replace('/([^а-яА-ЯЁёЪъa-zA-Z\s\-\,\.\:])/u', '', $str);

        // Убираем все что в скобке
        $str = preg_replace('/\(.*?\)/u', '', $str);

        $str = preg_replace('/\.\s{2,}./u', '.', $str);

        $str = preg_replace('/(Российская Федерация)|(Российской Федерации)/u', 'РФ', $str);

        print_r('<br>');
        print_r('<br>');
        print_r($str);
        dump($str);
        print_r('<br>');
        print_r('<br>');
        print_r(strlen($str));

        $keywords = preg_split("/[\s,]+/", $str);
        $i = 0;
        while ($i < count($keywords)) {
            if (mb_strlen($keywords[$i]) > 2) {

                /** @var Participles $prich */
                $prich = $this->getDoctrine()->getRepository(Participles::class)->word($keywords[$i]);
                if ($prich && mb_substr($keywords[$i], 0, strlen($keywords[$i]) - 2) == mb_substr($prich->getWord(), 0, strlen($keywords[$i]) - 2)) {
                    $pos = strpos($str, $prich->getWord());
                    $posZ = strpos(mb_substr($str, $pos), ',');
                    $str = str_replace(mb_substr($str, $pos, $posZ), ' ', $str);
                    $str = preg_replace('/\.\s{2,}./u', '.', $str);
                    $keywords = preg_split("/[\s,]+/", $str);
                }
            }
            $i++;
        }

        print_r('<br>');
        print_r('<br>');
        $str = preg_replace('/\.{2,}/u', '', $str);
        $str = preg_replace('/\.\s\./u', '.', $str);
        print_r($str);
        print_r('<br>');
        print_r('<br>');
        print_r(strlen($str));

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