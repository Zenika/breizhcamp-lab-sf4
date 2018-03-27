<?php

namespace App\Controller;

use DateTime;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DeLoreanController extends Controller
{
    /**
     * @Route("/",name="index")
     */
    public function index(Request $request)
    {
        $dt = $request->get('destination_time', new DateTime());
        $pt = $request->get('present_time', new DateTime());
        $ltd = $request->get('last_time_departed', new DateTime());

        return $this->render('delorean.dashboard.html.twig',
            [
                'destination_time' => $dt,
                'present_time' => $pt,
                'last_time_departed' => $ltd,

            ]
        );
    }

    /**
     * @Route("/book")
     * @param Request $request
     */
    public function book(ClientInterface $client, Request $request)
    {
        $response = $client->request('GET', 'http://nginx:82/evenements', [
                'headers' => [
                    //'accept'=> 'application/ld+json'
                    'Accept' => 'application/json'
                ]
            ]
        )->getBody();
        $evenements = json_decode($response, true);

        $choices = [];

        foreach ($evenements as $evenement) {
            $choices[$evenement['nom']] = $evenement['date'];
        }

        $form = $this->createFormBuilder()
            ->add('evenements', ChoiceType::class, ['choices' => $choices])
            ->add('save', SubmitType::class, array('label' => 'Réserver'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData()['evenements'];
            $dt = DateTime::createFromFormat(DateTime::RFC3339, $date);
            return $this->redirectToRoute('index', ['destination_time' => $dt]);
        }

        return $this->render('book.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/done", name="done")
     * @param Request $request
     */
    public function tripDone(Request $request, ClientInterface $client) {
        $date = $request->get('date');
        $response = $client->request('GET', 'http://nginx:82/evenements?date='.$date, [
                'headers' => [
                    //'accept'=> 'application/ld+json'
                    'Accept' => 'application/json'
                ]
            ]
        )->getBody();
        $evenement = json_decode($response, true);
        return $this->render('done.html.twig', [
            'image' => $evenement[0]['image']
        ]);
    }
}
