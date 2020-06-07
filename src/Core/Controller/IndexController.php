<?php

declare(strict_types=1);

namespace Core\Controller;

use Core\Entity\AdjectivesMorf;
use Core\Entity\File;
use Core\Entity\Instruction;
use Core\Entity\InstructionContent;
use Core\Entity\Item;
use Core\Entity\NewText1;
use Core\Entity\NounsMorf;
use Core\Entity\Post;
use Core\Entity\Section;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @param null $id
     * @return mixed|null
     */
    public function getBoss($id = null)
    {
        /** @var NewText1 $doljnost */
        $doljnost = $this->getDoctrine()->getRepository(NewText1::class)->find($id);
        $inst = $this->getDoctrine()->getRepository(InstructionContent::class)->findBy([
            'instruction' =>  $doljnost->getIntstr(),
            'section' => 1,
        ], ['id' => 'asc']);

        $data = [];
        /** @var InstructionContent $item */
        foreach ($inst as $item) {
            $data[] = $item->getItem()->getName();
        }

        $subject = implode(' ', $data);
        //dump($subject);
        $subject = preg_replace('|\s+|', ' ', str_replace("\n", " ", $subject));
        $subject = str_replace(['(', ')', ';'], '', $subject);

        $res = explode(' ', $subject);
        $start = null;
        $finish = null;
        foreach ($res as $k => $item) {
            if (mb_strtolower($item) == mb_strtolower('подчиняется')) {
                $start = $k;
            }

            if ($start && $k > $start) {
                if (stristr($item, '.') !== FALSE) {
                    $finish = $k;
                    break;
                }
            }
        }

        if ($finish) {
            $finish = count($res);
        }

        $pril = null;
        $sush = null;
        $nach = [];
        $ii = 0;
        for ($i = $start; $i < $finish; $i++) {
            if (in_array(mb_strtolower($res[$i]), ['и', 'или'])) {
                $ii++;
                continue;
            }

            $pril = $this->getDoctrine()->getRepository(AdjectivesMorf::class)->findOneBy(['word' => $res[$i], 'wcase' => 'дат']);

            if (!$pril) {
                $sush = $this->getDoctrine()->getRepository(NounsMorf::class)->findOneBy(['word' => $res[$i], 'wcase' => 'дат']);
            }

            if ($pril || $sush) {
                if (!isset($nach[$ii])) {
                    $nach[$ii] = '';
                }
                $nach[$ii] .= " " . $res[$i];
            }

        }

        return $nach[0] ?? null;
    }

    /**
     * @Route("/", name="home")
     * @Route("/api/", name="api")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var NewText1 $doljnost */
        $doljnost = $this->getDoctrine()->getRepository(NewText1::class)->find(237);
        $dolPol = $this->getDoctrine()->getRepository(InstructionContent::class)->findBy([
            'instruction' => $doljnost->getIntstr(),
            'section' => $doljnost->getSection()
        ]);

        if ($request->getRequestUri() == '/api/') {
            $res = [];
            /** @var InstructionContent $item */
            foreach ($dolPol as $item) {
                array_push($res, $item->getItem()->getName());
            }
            return new JsonResponse([
                'Должностная инструкция' => $doljnost,
                'Должностная инструкция расширенная' => $res,
            ]);
        }


        return $this->render('index.html.twig', [
            'doljnost' => $doljnost->getText(),
            'dolPol' => $dolPol,
            //'boss' => $this->getBoss(5),
        ]);
    }

    /**
     * @Route("/fix", name="fix")
     * @Route("/fix/{id}", name="fix_page")
     */
    public function fix($id = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository(Item::class)->findBetween($id * 1000+1,$id * 1000+1000);
        /** @var Item $item */
        foreach ($items as $item) {
            $name = preg_replace ('/^[0-9:;.\-\s\t]+/i', '', $item->getName());
            if ($name != $item->getName()) {
                $item->setName($name);

                $em->persist($item);
            }

            $em->flush();
        }
        return new Response('OK');
    }

    /**
     * @Route("/import", name="import")
     */
    public function import()
    {
        $em = $this->getDoctrine()->getManager();

        $nPost = 'ПРОГРАММИСТ';

        $post = $em->getRepository(Post::class)->findOneBy(['name' => $nPost]) ?? new Post();
        $post->setName($nPost);
        $em->persist($post);

        $file = new File();
        $file->setFilename('118.rtf');
        $file->setPost($post);
        $em->persist($file);

        $mainSection = $em->getRepository(Section::class)->find(1);

        $subject = '
1. Инженер-программист относится к категории специалистов.
 2. На должность: - инженера-программиста назначается лицо, имеющее высшее профессиональное (техническое или инженерно-экономическое) образование без предъявления требований к стажу работы или среднее профессиональное (техническое или инженерно-экономическое) образование и стаж работы в должности техника I категории не менее 3 лет либо других должностях, замещаемых специалистами со средним профессиональным образованием, не менее 5 лет; - инженера-программиста III категории - лицо, имеющее высшее профессиональное (техническое или инженерно-экономическое) образование и опыт работы по специальности, приобретенный в период обучения, или стаж работы на инженерно-технических должностях без квалификационной категории; - инженера-программиста II категории - лицо, имеющее высшее профессиональное (техническое или инженерно-экономическое) образование и стаж работы в должности инженера-программиста III категории или других инженерно-технических должностях, замещаемых специалистами с высшим профессиональным образованием, не менее 3 лет; - инженера-программиста I категории - лицо, имеющее высшее профессиональное (техническое или инженерно-экономическое) образование и стаж работы в должности инженера-программиста II категории не менее 3 лет. 
 4.1. Руководящие и нормативные материалы, регламентирующие методы разработки алгоритмов и программ и использования вычислительной техники при обработке информации.
4.2. Основные принципы структурного программирования.
4.3. Виды программного обеспечения.
4.4. Технико-эксплуатационные характеристики, конструктивны особенности, назначение и режимы работы ЭВМ, правила ее технической эксплуатации.
4.5. Технологию автоматической обработки информации и кодирования информации.
4.6. Формализованные языки программирования
4.7. Действующие стандарты, системы счислений, шифров и кодов.
4.8. Порядок оформления технической документации.
4.9. Передовой отечественный и зарубежный опыт программирования и использования вычислительной техники.
4.10. Основы экономики, организации производства, труда и управления.
4.11. Основы трудового законодательства.
4.12. Правила внутреннего трудового распорядка.
4.13. Правила и нормы охраны труда.
6. На время отсутствия инженера-программиста (отпуск, болезнь, пр.) его обязанности исполняет лицо, назначенное в установленном порядке. Данное лицо приобретает соответствующие права и несет ответственность за качественное и своевременное исполнение возложенных на него обязанностей.
        ';
        $this->fill($em, $post, $mainSection, $subject);

        $obyazSection = $em->getRepository(Section::class)->find(2);

        $subject = '
1. На основе анализа математических моделей и алгоритмов решения экономических и других задач разрабатывает программы, обеспечивающие возможность выполнения алгоритма и соответственно поставленной задачи средствами вычислительной техники, проводит их тестирование и отладку.
2. Разрабатывает технологию решения задачи по всем этапам обработки информации.
3. Осуществляет выбор языка программирования для описания алгоритмов и структур данных.
4. Определяет информацию, подлежащую обработке средствами вычислительной техники, ее объемы, структуру, макеты и схемы ввода, обработки, хранения и вывода, методы ее контроля.
5. Выполняет работу по подготовке программ к отладке и проводит отладку.
6. Определяет объем и содержание данных контрольных примеров, обеспечивающих наиболее полную проверку соответствия программ их функциональному назначению.
7. Осуществляет запуск отлаженных программ и ввод исходных данных, определяемых условиями поставленных задач.
8. Проводит корректировку разработанной программы на основе анализа выходных данных.
9. Разрабатывает инструкции по работе с программами, оформляет необходимую техническую документацию.
10. Определяет возможность использования готовых программных продуктов.
11. Осуществляет сопровождение внедрения программ и программных средств.
12. Разрабатывает и внедряет системы автоматической проверки правильности программ, типовые и стандартные программные средства, составляет технологию обработки информации.
13. Выполняет работу по унификации и типизации вычислительных процессов.
14. Принимает участие в создании каталогов и картотек стандартных программ, в разработке форм документов, подлежащих машинной обработке, в проектировании программ, позволяющих расширить область применения вычислительной техники.
     ';
        $this->fill($em, $post, $obyazSection, $subject);

        $pravaSection = $em->getRepository(Section::class)->find(3);

        $subject = '
1. Знакомиться с проектами решений руководства предприятия, касающихся его деятельности.
2. Вносить на рассмотрение руководства предложения по совершенствованию работы, связанной с предусмотренными настоящей инструкцией обязанностями.
3. В пределах своей компетенции сообщать своему непосредственному руководителю о всех выявленных в процессе осуществления должностных обязанностей недостатках в деятельности предприятия (его структурных подразделениях) и вносить предложения по их устранению.
4. Запрашивать лично или по поручению своего непосредственного руководителя от специалистов подразделений информацию и документы, необходимые для выполнения его должностных обязанностей.
5. Привлекать специалистов всех (отдельных) структурных подразделений к решению задач, возложенных на него (если это предусмотрено положениями о структурных подразделениях, если нет - то с разрешения их руководителей).
6. Требовать от своего непосредственного руководителя, руководства предприятия оказания содействия в исполнении им своих должностных обязанностей и прав.
     ';
        $this->fill($em, $post, $pravaSection, $subject);

        $otvetSection = $em->getRepository(Section::class)->find(4);

        $subject = '
1. За ненадлежащее исполнение или неисполнение своих должностных обязанностей, предусмотренных настоящей должностной инструкцией - в пределах, определенных действующим трудовым законодательством Российской Федерации.
2. За правонарушения, совершенные в процессе осуществления своей деятельности - в пределах, определенных действующим административным, уголовным и гражданским законодательством Российской Федерации.
3. За причинение материального ущерба - в пределах, определенных действующим трудовым и гражданским законодательством Российской Федерации.
    ';
        $this->fill($em, $post, $otvetSection, $subject);

        $em->flush();

        return new Response('OK');
    }

    public function fill($em, $post, $section, $subject)
    {
        echo '<pre>';
        echo $section->getName() . PHP_EOL;

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $subject) as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $item = new Item();
            $item->setName($line);
            $em->persist($post);

            echo $line . PHP_EOL;

            $instr = new Instruction();
            $instr
                ->setPost($post)
                ->setSection($section)
                ->setItem($item);

            $em->persist($instr);
        }
        echo '</pre>';
    }
}
