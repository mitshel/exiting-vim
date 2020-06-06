<?php


namespace Core\Controller;


use Core\Entity\Instruction;
use Core\Entity\InstructionContent;
use Core\Entity\Participles;
use Core\Entity\Verbs;
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
        $items = $this->getDoctrine()->getRepository(InstructionContent::class)->findArr(1, 2);

        $arr = array_column($items, 'name');

        $str = implode(' ', $arr);
        dump($str);
        dump(iconv_strlen($str));
        $str = $this->regex($str);

        $keywords = preg_split("/[\s,]+/", $str);
        $i = 0;
        while ($i < count($keywords)) {
            if (iconv_strlen($keywords[$i]) > 2) {
                /** @var Participles $prich */
                $prich = $this->getDoctrine()->getRepository(Participles::class)->word($keywords[$i]);
                if ($prich && iconv_substr($keywords[$i], 0, iconv_strlen($keywords[$i]) - 2) == iconv_substr($prich->getWord(), 0, iconv_strlen($keywords[$i]) - 2)) {
                    $pos = iconv_strpos($str, $keywords[$i]) + iconv_strlen($keywords[$i]);
                    $pos += iconv_strpos(iconv_substr($str, $pos), ',');

                    $posZ = $pos + iconv_strpos(iconv_substr($str, $pos), ',');

                    $posZ2 = $pos + iconv_strpos(iconv_substr($str, $pos), '.') ;
                    $str = str_replace(iconv_substr($str, $pos, min($posZ, $posZ2)), '', $str);

                    $str = $this->regex($str);
                    $keywords = preg_split("/[\s,]+/", $str);
                }
            }
            $i++;
        }

        $str = $this->regex($str);
        dump($str);
        dump(iconv_strlen($str));
        $str = $this->pred($str);
        dump(iconv_strlen($str));

        return $this->render('slova.html.twig');
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
                    $verb = $this->getDoctrine()->getRepository(Verbs::class)->word2($slv);
                    if (!$verb) {
                        foreach ($slova as $k => &$slv2) {
                            $verb2 = $this->getDoctrine()->getRepository(Verbs::class)->word2($slv2);
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

                /*

                if (!$verb){

                }*/
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

        if ($str[0] == '.') {
            $str = iconv_substr($str, 1);

        }

        $str = str_replace('. .', '.', $str);

        $str = trim($str);

        return $str;
    }
}