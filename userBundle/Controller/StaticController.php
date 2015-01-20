<?php

namespace eclore\userBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:Project');
        $projs = $repository->findBy(array(), array('id'=>'DESC'));
        $repository = $em->getRepository('ecloreuserBundle:NewsPost');
        $posts = $repository->findBy(array(), array('id'=>'DESC'));
            
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
    
        public function displayNewsPostsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:NewsPost');
        $posts = $repository->getCurrentNewsPosts();
            
        return $this->render('ecloreuserBundle:Static:newsposts.html.twig',
        array('posts' => $posts));
    
    }

    
}
