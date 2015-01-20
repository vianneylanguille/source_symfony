<?php

namespace eclore\userBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:Project');
        $projs = $repository->findAll();
        $repository = $em->getRepository('ecloreuserBundle:NewsPost');
        $posts = $repository->findAll();
            
        return $this->render('ecloreuserBundle::index.html.twig', array('projs'=>$projs, 'posts'=>$posts));
    }
    
    public function displayNewsPostAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:NewsPost');
        $post = $repository->find($id);
        
        if(!$post){
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Cette page n\'existe pas!');
            return $this->redirect($this->generateUrl('ecloreuser_homepage'));
            }
            
        return $this->render('ecloreuserBundle:Static:newspost.html.twig',
        array('post' => $post));
    
    }

    
}
