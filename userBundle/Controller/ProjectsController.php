<?php

namespace eclore\userBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class ProjectsController extends Controller
{
    public function rechercherAction()
    { $request = $this->container->get('request');  
        
        if($request->isXmlHttpRequest())
        {
            $projects = $this->getDoctrine()
                   ->getManager()
                   ->createQuery('select p FROM ecloreuserBundle:Project p
                   WHERE p.endDate > :date AND p.enabled = :enabled')
                   ->setParameter('date', new \DateTime())
                   ->setParameter('enabled', true)
                   ->getResult();
            $serializer = $this->container->get('jms_serializer');
            $response = new Response();
            $response->setContent($serializer->serialize($projects, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
            /*
            [{"id":1,"project_name":"sdfgdsg","description":"sdrgsdg",
            "association":{"id":1,"associationName":"banqure alim"},
            "project_applications":0,"start_date":"2014-01-01T00:00:00+0000","end_date":"2017-01-01T00:00:00+0000",
            "labels":{"id":1,"name":"social"},"address":"Aix-en-Provence, France","city":"Aix-en-Provence",
            "country":"France","lat":"43.529742","lng":"5.4474270000000615"}]
            */
        }else
        {
            $repository = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('ecloreuserBundle:ProjectLabels');
            $labels = $repository->findAll();  
                    
            return $this->render('ecloreuserBundle:Projects:rechercher.html.twig');
        }
    }
    
    
    public function showProjectAction($id)
    {
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('ecloreuserBundle:Project');
        $project = $repository->find($id);
        //get timeline
        $actionManager = $this->get('spy_timeline.action_manager');
        $subject = $actionManager->findOrCreateComponent($project);
        $timelineManager = $this->get('spy_timeline.timeline_manager');
        $timeline = $timelineManager->getTimeline($subject, array('page' => 1, 'max_per_page' => '10', 'paginate' => true));
        
    return $this->render('ecloreuserBundle:Projects:show-project.html.twig', array('proj'=>$project, 'timeline_coll'=>$timeline));
    }
    
    
}
