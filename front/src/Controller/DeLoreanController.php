<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/trip")
     */
    public function trip(Request $request)
    {
      $req = $this->get('request')->request;
      $destinationTime = $req->get('destination_time');
      $presentTime = $req->get('present_time');
      $lastTimeDeparted = $req->get('last_time');
        return $this->render('delorean.dashboard.html.twig',
        [
          'destination_time' => $presentTime,
          'present_time' => $destinationTime,
          'last_time_departed'=>$presentTime

        ]
      );
    }
}
