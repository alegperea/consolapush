<?php

namespace APP\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use APP\UsuarioBundle\Entity\Perfil;
use APP\UsuarioBundle\Form\PerfilType;

/**
 * Perfil controller.
 *
 */
class PerfilController extends Controller
{
    /**
     * Lists all Perfil entities.
     *
     */
    public function indexAction()
    {
        $this->get('session')->getFlashBag()->clear();
        
        return $this->__renderIndex();
    }
    
    private function __renderIndex()
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.context')->isGranted('ROLE_CONFIGURACION')) {
            $entities = $em->getRepository('UsuarioBundle:Perfil')->findAll();
        }
        else{
            $entities = $em->getRepository("UsuarioBundle:Perfil")->findByActivo(true);
        }

        return $this->render('UsuarioBundle:Perfil:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Perfil entity.
     *
     */
    public function showAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Perfil.');
        }

        return $this->render('UsuarioBundle:Perfil:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to create a new Perfil entity.
     *
     */
    public function newAction()
    {
        $this->get('session')->getFlashBag()->clear();
        $entity = new Perfil();
        $form   = $this->createForm(new PerfilType(), $entity);

        return $this->render('UsuarioBundle:Perfil:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Perfil entity.
     *
     */
    public function createAction()
    {
        $this->get('session')->getFlashBag()->clear();
        $em = $this->getDoctrine()->getManager();
        $entity  = new Perfil();
        $request = $this->getRequest();
        $form    = $this->createForm(new PerfilType(), $entity);
        $form->submit($request);
        $data = $request->request->get('pac_usuariobundle_perfiltype');

        if ($form->isValid()) {
            if ( count($entity->getRolesArray()) == 0 ){
                $this->setFlash('error','Debe elegir por lo menos un Rol');
                return $this->render('UsuarioBundle:Perfil:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView()
                ));
            }
            if (array_key_exists('roles', $data)){
                $roles_seleccionados = $data['roles'];
                if ( !$this->pagInicioDefaultValid($roles_seleccionados)){
                    $this->setFlash('error','Debe elegir una página inicio default que esté relacionada con el Rol');
                    return $this->render('UsuarioBundle:Perfil:new.html.twig', array(
                        'entity' => $entity,
                        'form'   => $form->createView()
                    ));
                }
            }
            $em = $this->getDoctrine()->getManager();
            $entity->setFechaAlta(new \DateTime());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('perfil_show', array('id' => $entity->getId())));
            
        }
        $this->setFlash('error','No se han realizado los siguientes cambios.');

        return $this->render('UsuarioBundle:Perfil:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }
    
    private function pagInicioDefaultValid($roles_id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->get('pac_usuariobundle_perfiltype');
        $pagina_inicio_default = $em->getRepository("UsuarioBundle:PaginaInicio")->find($data['pagina_inicio_default'])->getNombre();
        $roles = $em->getRepository("UsuarioBundle:Rol")->findRoles($roles_id);
        return true;
        foreach ( $roles as $rol ){
            if ( $rol->getNombre() == "ROLE_$pagina_inicio_default") return true;
        }
        return false;
    }
    
    /**
     * Displays a form to edit an existing Perfil entity.
     *
     */
    public function editAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Perfil.');
        }

        $editForm = $this->createForm(new PerfilType(), $entity);

        return $this->render('UsuarioBundle:Perfil:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Perfil entity.
     *
     */
    public function updateAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Perfil.');
        }

        $editForm   = $this->createForm(new PerfilType(), $entity);

        $request = $this->getRequest();
        
        $data = $request->request->get('pac_usuariobundle_perfiltype');

        $editForm->submit($request);

        if ($editForm->isValid()) {
            if ( !array_key_exists('roles', $data) ){
                $this->setFlash('error','Debe elegir por lo menos un Rol');
                return $this->render('UsuarioBundle:Perfil:edit.html.twig', array(
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                ));
            } 
            if (array_key_exists('roles', $data)){
                $roles_seleccionados = $data['roles'];
                if ( !$this->pagInicioDefaultValid($roles_seleccionados)){
                    $this->setFlash('error','Debe elegir una página inicio default que esté relacionada con el Rol');
                    return $this->render('UsuarioBundle:Perfil:edit.html.twig', array(
                        'entity'      => $entity,
                        'edit_form'   => $editForm->createView(),
                    ));
                }
            }
            $em->persist($entity);
            $em->flush();

            $this->setFlash('success','Los cambios se han realizado con éxito');
            return $this->render('UsuarioBundle:Perfil:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            ));
        }
        
        $this->setFlash('error','No se han podido realizar los siguientes cambios.');
        return $this->render('UsuarioBundle:Perfil:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    
    public function deleteAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se pudo encontrar la entidad Perfil.');
        }
        
        if ( $entity->isPerfilDefault() ) {
            $this->setFlash('error','No se puede eliminar un Perfil definido por el Sistema');
            return $this->__renderIndex();
        }
        
        if ( $em->getRepository("UsuarioBundle:Usuario")->findBy(array('activo' => true, 'perfil' => $id))){
            $this->setFlash('error','No se puede eliminar el Perfil que esté siendo utilizado por un Usuario');
            return $this->__renderIndex();
        }

        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity->setUsuarioBaja($usuario);
        $entity->setFechaBaja(new \DateTime());
        $entity->setActivo(false);

        $em->persist($entity);
        $em->flush();
        
        $this->setFlash('success','El Perfil '.$entity->getNombre().' fue desactivado con éxito.');

        return $this->__renderIndex();
    }
    
    public function restoreAction($id)
    {
        $this->get('session')->getFlashBag()->clear();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Perfil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se pudo encontrar la entidad Perfil.');
        }

        $entity->setActivo(true);

        $em->persist($entity);
        $em->flush();
        
        $this->setFlash('success','El Perfil '.$entity->getNombre().' fue activado con éxito.');

        return $this->__renderIndex();
    }
    
    private function setFlash($index,$message) {
        $this->get('session')->getFlashBag()->clear();
        $this->get('session')->getFlashBag()->add($index,$message);
    }
}
