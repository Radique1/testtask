<?php

namespace App\Controller;

use App\Builder\Builder;
use App\Form\CarOrderType;
use App\Logger\Logger;
use App\Logger\Mailer;
use App\Manager\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function makeOrder(Request $request): Response
    {
        $form = $this->createForm(CarOrderType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = new Manager();
            $builder = new Builder();
            $formData = $form->getData();

            $manager->setBuilder($builder);
            $manager->handleOrder($formData);

            $newHtml = $manager->getVehicle()->printCharacteristics();

            return $this->render('base.html.twig', ['html' => $newHtml]);
        }

        return $this->render('base.html.twig', ['form' => $form->createView()]);
    }
}