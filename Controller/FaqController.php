<?php

namespace BviFaqBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BviFaqBundle\Form\FaqForm;
use BviFaqBundle\Entity\Faq;
use DateTime;

/**
 * Class Faq
 */
class FaqController extends Controller
{
    /**
     * 
     * @param Request $request
     * @return Response
     */
    
    public function indexAction(Request $request) {
        
        $lstObj = $this->prepareListObj($request);
        $lstObj->setTemplate('BviFaqBundle:AjaxPagination:ajax_pagination.html.twig');
        
        if ($request->isXmlHttpRequest()) {
            
            $listView          =  $this->renderView('BviFaqBundle:Faq:_list.html.twig',array('lstObj' => $lstObj));
            $output['success'] = true;
            $output['listView']= $listView;
            $response = new Response(json_encode($output));
            return $response;
            
        }else{
            return $this->render('BviFaqBundle:Faq:index.html.twig',array('lstObj' => $lstObj));
        }
        
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function prepareListObj(Request $request) {
        
        $em        = $this->getDoctrine()->getManager();
        $params    = $this->get('request')->request->all();
        
        $qry       = $em->getRepository('BviFaqBundle:Faq')->getList($params);

        $itmPerPge = 20;
        // Creating pagnination
        $pagination = $this->get('knp_paginator')->paginate(
                $qry, $this->get('request')->query->get('page', 1), $itmPerPge
        );
        
        return $pagination;
    }
    
    //add faq page
    
    public function newAction(Request $request) {
        
        $objFaq = new Faq();
        $form = $this->createForm(new FaqForm(), $objFaq);
        
        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $objFaq->setCreatedat(new DateTime());
                $user = $this->get('security.context')->getToken()->getUser();
                if (is_object($user)) {
                    $objFaq->setCreatedby($user->getId());
                }else{
                    $objFaq->setCreatedby(1);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($objFaq);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', "Record has been added successfully.");
                return $this->redirect($this->generateUrl('bvi_faq_list'));
            }
        }
        
        return $this->render('BviFaqBundle:Faq:new.html.twig', array(
                    'form' => $form->createView()
        ));
    }
    
    //edit faq page
    
    public function editAction(Request $request,$id = '') {
        
        $em = $this->getDoctrine()->getManager();
        $objFaq = $em->getRepository('BviFaqBundle:Faq')->find($id);
        
        if (!$objFaq) {

            $this->get('session')->getFlashBag()->add('failure', "Faq Page does not exist.");
            return $this->redirect($this->generateUrl('bvi_faq_list'));
        }
        $form = $this->createForm(new FaqForm(), $objFaq);

        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $objFaq->setUpdatedat(new DateTime());
                $user = $this->get('security.context')->getToken()->getUser();
                if (is_object($user)) {
                    $objFaq->setUpdatedby($user->getId());
                }
                $em->persist($objFaq);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', "Record has been updated successfully.");
                return $this->redirect($this->generateUrl('bvi_faq_list'));
            }
        }
        return $this->render('BviFaqBundle:Faq:edit.html.twig', array(
                    'form' => $form->createView(),'objFaq' => $objFaq
        ));
    }
    
    //update status of faq page
    
    public function updateStatusAction(Request $request) {
        
        $em     = $this->getDoctrine()->getManager();
        $id     = $request->get('id');
        $objFaq = $em->getRepository('BviFaqBundle:Faq')->find($id);
        $success= false;
        
        if (is_object($objFaq)) {
            
            $status = $objFaq->getStatus() == 'Active' ? 'Inactive' : 'Active';
            $objFaq->setStatus($status);
            $objFaq->setUpdatedat(new DateTime());
            $user = $this->get('security.context')->getToken()->getUser();
            if (is_object($user)) {
                $objFaq->setUpdatedby($user->getId());
            }
            $em->persist($objFaq);
            $em->flush();
            $success = true;
        }
        
        $output['success'] = $success;
        $output['msg']     = 'Record updated successfully';
        $response          = new Response(json_encode($output));
        return $response;
    }    
}
