<?php


namespace Core\Controller;


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
        /** @var Words $slovo */
        $slova = $this->getDoctrine()->getRepository(Words::class)->word('компьютер');

        foreach ($slova as $slovo) {
            dump($slovo->getWord());
        }


        return $this->render('index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}