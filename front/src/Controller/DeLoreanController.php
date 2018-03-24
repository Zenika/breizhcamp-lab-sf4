<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DeLoreanController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('delorean.dashboard.html.twig',
        [
          'destination_time' => 0,
          'present_time' => time(),
          'last_time_departed'=>time()

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
}
