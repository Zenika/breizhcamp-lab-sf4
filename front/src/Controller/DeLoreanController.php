<?php

namespace App\Controller;

use DateTime;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Date;

class DeLoreanController extends Controller
{
    /**
     * @Route("/",name="index")
     */
    public function index(Request $request)
    {
        $dt = $request->get('destination_time',0);
        $pt = $request->get('present_time',time());
        $ltd = $request->get('last_time_departed',time());

        return $this->render('delorean.dashboard.html.twig',
        [
          'destination_time' => $dt,
          'present_time' => $pt,
          'last_time_departed'=>$ltd,

        ]
      );
    }

    /**
     * @Route("/miaou")
     */
    public function change(Request $request)
    {

      $presentTime = (int)$request->request->get('present_time');
      //$destinationTime = (int)$request->request->get('destination_time');
      //$lastTimeDeparted = (int)$request->request->get('last_time_departed');

      return $this->render('delorean.dashboard.html.twig',
      [
        'destination_time' => 0,
        'present_time' => $presentTime+1,
        'last_time_departed'=>0

      ]
    );
    }

    /**
     * @Route("/trip")
     */
    public function trip(Request $request)
    {
      $query = $request->query;
      $destinationTime = $query->get('destinationTime');
      
      $presentTime = $query->get('presentTime');
      $lastTimeDeparted = $query->get('lastTime');
        return $this->render('delorean.dashboard.html.twig',
        [
          'destination_time' => $destinationTime,
          'present_time' => $destinationTime,
          'last_time_departed'=>$presentTime

        ]
      );
    }

    /**
     * @Route("/book")
     * @param Request $request
     */
    public function book(ClientInterface $client,Request $request)
    {
        $response = $client->request('GET','http://nginx:82/api/evenements',[
            'headers'=>[
                //'accept'=> 'application/ld+json'
                'Accept'=> 'application/json'
            ]
            ]
        )->getBody();
        $evenements=json_decode($response,true);

        $choices = [];

        foreach ($evenements as $evenement) {
            $choices[$evenement['nom']] = $evenement['date'];
        }

        $form = $this->createFormBuilder()
            ->add('evenements',ChoiceType::class,['choices'=>$choices])
            ->add('save', SubmitType::class, array('label' => 'Réserver'))
        ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date= $form->getData()['evenements'];

            $dt = DateTime::createFromFormat( DateTime::RFC3339,$date);

            $timestamp = $dt->getTimestamp();
            return $this->redirectToRoute('index',['destination_time'=>$timestamp]);
        }

        return $this->render('book.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
