<?php

namespace APP\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use APP\UsuarioBundle\Entity\Rol;
use APP\UsuarioBundle\Form\RolType;

/**
 * Rol controller.
 *
 */
class RolController extends Controller
{
    /**
     * Lists all Rol entities.
     *
     */
    public function indexAction()
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UsuarioBundle:Rol')->findAll();

        return $this->render('UsuarioBundle:Rol:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /*
    public function showAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Rol.');
        }

        return $this->render('UsuarioBundle:Rol:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

   
    public function newAction()
    {
        $this->get('session')->getFlashBag()->clear();
        
        $entity = new Rol();
        $form   = $this->createForm(new RolType(), $entity);

        return $this->render('UsuarioBundle:Rol:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

   
    public function createAction()
    {
        $this->get('session')->getFlashBag()->clear();
        
        $entity  = new Rol();
        $request = $this->getRequest();
        $form    = $this->createForm(new RolType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $usuario = $this->get('security.context')->getToken()->getUser();
            $entity->setUsuarioAlta($usuario);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rol_show', array('id' => $entity->getId())));
            
        }

        return $this->render('UsuarioBundle:Rol:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    
    public function editAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Rol.');
        }

        $editForm = $this->createForm(new RolType(), $entity);

        return $this->render('UsuarioBundle:Rol:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

   
    public function updateAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Rol.');
        }

        $editForm   = $this->createForm(new RolType(), $entity);

        $request = $this->getRequest();

        $data = $request->request->get('pac_usuariobundle_roltype');
        
        if ( !$data['nombre'] ){
            $this->setFlash('error','Debe ingresar un Nombre');
            return $this->render('UsuarioBundle:Rol:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            ));
        }
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            $this->setFlash('success','Los cambios se han realizado con éxito.');

            return $this->render('UsuarioBundle:Rol:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            ));
        }
        
        $this->setFlash('error','No se han podido realizar los siguientes cambios.');
        
        return $this->render('UsuarioBundle:Rol:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    public function deleteAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Rol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se pudo encontrar la entidad Rol.');
        }

        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity->setUsuarioBaja($usuario);
        $entity->setFechaBaja(new \DateTime());
        $entity->setActivo(false);

        $em->persist($entity);
        $em->flush();
        
        $this->setFlash('success','El Rol '.$entity->getNombre().' fue eliminado con éxito.');

        $entities = $em->getRepository('UsuarioBundle:Rol')->findAll();

        return $this->render('UsuarioBundle:Rol:index.html.twig', array(
            'entities' => $entities
        ));
    }

     * 
     */
    private function setFlash($index,$message)
    {
        $this->get('session')->getFlashBag()->clear();
        $this->get('session')->getFlashBag()->add($index,$message);
    }
    
}