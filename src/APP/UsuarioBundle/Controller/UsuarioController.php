<?php

namespace APP\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use APP\UsuarioBundle\Entity\Usuario;
use APP\UsuarioBundle\Form\UsuarioType;
use APP\UsuarioBundle\Form\MiPerfilType;
use APP\UsuarioBundle\Form\MiPerfilPassType;
use APP\UsuarioBundle\Form\UsuarioPassType;
use APP\UsuarioBundle\Form\UsuarioPassNoOldType;
use Symfony\Component\HttpFoundation\Request;
use APP\UsuarioBundle\Form\MyPerfilAceType;
use APP\UsuarioBundle\Entity\Perfil;
use APP\CoreBundle\Entity\EstadoEncargado;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller {

    /**
     * Lists all Usuario entities.
     *
     */
    public function indexAction() {
        $this->get('session')->getFlashBag()->clear();

        return $this->__renderIndex();
    }

    private function __renderIndex() {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        if ($this->get('security.context')->isGranted('ROLE_CONFIGURACION')) {
            $entities = $em->getRepository("UsuarioBundle:Usuario")->findAll();
        } else {
            $entities = $em->getRepository('UsuarioBundle:Usuario')->findBy(array( "eliminado" => false, "usuarioActualizacion" => $usuario->getId()));
        }

        return $this->render('UsuarioBundle:Usuario:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Usuario entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Usuario.');
        }

        return $this->render('UsuarioBundle:Usuario:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    public function showPerfilAceAction(Request $request) {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $passwordAnterior = $usuario->getPassword();

        $form = $this->createForm(new MyPerfilAceType(), $usuario);

        $errors = array();
        if ($request->getMethod() == "POST") {
            $form->submit($request);
            $validator = $this->get('validator');
            $errors = $validator->validate($usuario, array('editmyperfil'));
            if ($form->isValid() || count($errors) == 0) {
                $em = $this->getDoctrine()->getManager();
                $usuario->setFechaActualizacion();
                $usuario->subirFoto($this->container->getParameter('anfler.directorio.imagenes'));

                /// CONTRASEÑA ///
                $data = $request->request->get('anfler_usuariobundle_myperfilacetype');
                $oldPassword = $data['oldpassword'];
                $passwordOriginal = $data['password'];
                $password_nueva = $passwordOriginal['first'];
                $password_nueva_second = $passwordOriginal['second'];
                if ($oldPassword != "" || $password_nueva != "" || $password_nueva_second != "") {
                    $usuario->setPassword($passwordAnterior);
                    if (!$this->isPassValid($data,$usuario)) {
                        return $this->render('UsuarioBundle:Usuario\MyPerfil:showAce.html.twig', array(
                                    'entity' => $usuario,
                                    'formulario' => $form->createView(),
                                    'tab_contraseña' => true
                        ));
                    }
                    //$usuario->setCambioPw(true);
                    $old_passwordCodificado = $this->get('security.encoder_factory')
                                    ->getEncoder($usuario)->encodePassword(
                            $oldPassword, $usuario->getSalt()
                    );
                    if ($password_nueva == "") {
                        $this->setFlash('error', 'La nueva contraseña no puede estar en blanco.');
                        return $this->render('UsuarioBundle:Usuario\MyPerfil:showAce.html.twig', array(
                                    'entity' => $usuario,
                                    'formulario' => $form->createView(),
                                    'tab_contraseña' => true
                        ));
                    }
                    if ($passwordAnterior != $old_passwordCodificado) {
                        $this->setFlash('error', 'La contraseña actual que has introducido no es correcta.');
                        return $this->render('UsuarioBundle:Usuario\MyPerfil:showAce.html.twig', array(
                                    'entity' => $usuario,
                                    'formulario' => $form->createView(),
                                    'tab_contraseña' => true
                        ));
                    }
                    $encoder = $this->get('security.encoder_factory')
                            ->getEncoder($usuario);
                    $usuario->setSalt(md5(time()));
                    $passwordCodificado = $encoder->encodePassword(
                            $password_nueva, $usuario->getSalt()
                    );
                    $usuario->setPassword($passwordCodificado);
                } else {
                    $usuario->setPassword($passwordAnterior);
                }

                /// END OF CONTRASEÑA ///

                $em->persist($usuario);
                $em->flush();

                $this->setFlash('info', 'Los datos de tu perfil se han actualizado correctamente');
                return $this->redirect($this->generateUrl('usuario_showperfilace'));
            }
        }

        return $this->render("UsuarioBundle:Usuario\MyPerfil:showAce.html.twig", array(
                    'formulario' => $form->createView(),
                    'errors' => $errors
        ));
    }
    
    /**
     * Displays a form to create a new Usuario entity.
     *
     */
    public function newAction() {
        $usuario = $this->get('security.context')->getToken()->getUser();
        /*if (!$usuario->isPerfilAdministrador()) {
            throw $this->createNotFoundException('No tiene permisos para crear un Usuario.');
        }*/
        $this->get('session')->getFlashBag()->clear();

        $entity = new Usuario();
        $form = $this->createForm(new UsuarioType($usuario), $entity);

        return $this->render('UsuarioBundle:Usuario:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView()
        ));
    }

    /**
     * Creates a new Usuario entity.
     *
     */
    public function createAction() {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $this->get('session')->getFlashBag()->clear();
        $em = $this->getDoctrine()->getManager();
        $entity = new Usuario();
        $request = $this->getRequest();
        $form = $this->createForm(new UsuarioType($usuario), $entity);
        $data = $request->request->get('pac_usuariobundle_usuariotype');

        if ($request->getMethod() == 'POST') {
            $form->submit($request);
            if ($form->isValid()) {
                if (!$this->codIdentValid($data)) {
                    return $this->render('UsuarioBundle:Usuario:new.html.twig', array(
                                'entity' => $entity,
                                'form' => $form->createView()
                    ));
                }
                $entity->setUsuarioAlta($usuario);
                $password_original = $entity->getPassword();

                $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($entity);
                $entity->setSalt(md5(time()));
                $passwordCodificado = $encoder->encodePassword(
                        $entity->getPassword(), $entity->getSalt()
                );
                $entity->setFechaActualizacion(new \DateTime());
                $entity->setPassword($passwordCodificado);
                $entity->setPaginaInicio($entity->getPerfil()->getPaginaInicioDefault());

                if (isset($data['enviar_mail']) && $data['enviar_mail'] && $data['enviar_mail'] == "1") {
                    $this->__enviarMail(
                            array(
                                'from' => $this->container->getParameter('mailer_user'),
                                'to' => $entity->getEmail(),
                                'subject' => 'Alta de usuario',
                                'body' => $this->bodyMailAltaUsuario($entity->getUsername(), $password_original)
                    ));
                }

                $em->persist($entity);
                $em->flush();
//                return $this->redirect($this->generateUrl('admin_home'));
                $this->setFlash('info', 'La creación del Usuario se realizó de manera correcta.');
                return $this->redirect($this->generateUrl('usuario_show', array('id' => $entity->getId())));
            }
        }
        $this->setFlash('error', 'Formulario invalido.');
        return $this->render('UsuarioBundle:Usuario:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView()
        ));
    }

    private function codPostValid($data) {
        $em = $this->getDoctrine()->getManager();
        $cp = $em->getRepository('UbicacionBundle:CodigoPostal')->findByCalleYAltura($data['calle'], $data['direccionNro']);
        if (!$cp) {
            $this->setFlash('error', "Codigo Postal incorrecto para la dirección ingresada.");
            return false;
        }

        return true;
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
    public function editAction($id) {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $this->get('session')->getFlashBag()->clear();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Usuario.');
        }
        $paginas_inicio = $this->getPaginasInicio($entity->getPerfil());
        $formulario = $this->createForm(new UsuarioType($usuario, $paginas_inicio), $entity);
        return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
                    'entity' => $entity,
                    'formulario' => $formulario->createView(),
        ));
    }

    private function getPaginasInicio($perfil) {
        $roles = $perfil->getRolesArray();
        $paginas_inicio = "(";
        foreach ($roles as $rol) {
            $paginas_inicio = $paginas_inicio . "'" . substr($rol, 5, strlen($rol)) . "',";
        }
        $paginas_inicio = substr($paginas_inicio, 0, strlen($paginas_inicio) - 2) . "')";
        return $paginas_inicio;
    }

    public function paginasInicioAction() {
        $em = $this->getDoctrine()->getManager();
        $perfil = $em->getRepository("UsuarioBundle:Perfil")->find($this->getRequest()->get('perfil'));
        $paginas_inicio = $em->getRepository("UsuarioBundle:Usuario")->findPaginasInicio($this->getPaginasInicio($perfil));

        return $this->render("UsuarioBundle:Usuario:paginasInicio.html.twig", array(
                    'paginas_inicio' => $paginas_inicio
        ));
    }

    /**
     * Edits an existing Usuario entity.
     *
     */
    public function updateAction($id) {
        $this->get('session')->getFlashBag()->clear();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Usuario.');
        }
        $formulario = $this->createForm(new UsuarioType($usuario, $this->getPaginasInicio($entity->getPerfil())), $entity);
        $request = $this->getRequest();
        $perfil_anterior = $entity->getPerfil();
        $formulario->submit($request);
        $data = $request->request->get('pac_usuariobundle_usuariotype');
        if ($formulario->isValid()) {
            if (!$this->codIdentValid($data)) {
                return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
                            'entity' => $entity,
                            'formulario' => $formulario->createView(),
                ));
            }/*
            if (isset($data['areas'])){
                foreach ($data['areas'] as $area_id){
                    $area = $em->getRepository('PortalBundle:Area')->find($area_id);
                    if ($area){
                        $entity->addArea($area);
                    }
                }
            }*/
            if ($perfil_anterior->getId() != $entity->getPerfil()->getId())
                $entity->setPaginaInicio($entity->getPerfil()->getPaginaInicioDefault());
            /*
              if ( !$this->pagInicioValid($entity->getPerfil()->getRolesArray())){
              $this->setFlash('error','Debe elegir una página de inicio que esté relacionado con el Perfil del Usuario');
              return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
              'entity'      => $entity,
              'formulario'  => $formulario->createView(),
              ));
              }
             */
            $em->persist($entity);
            $em->flush();
            $this->setFlash('success', 'Los datos de tu perfil se han actualizado correctamente');
            return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
                        'entity' => $entity,
                        'formulario' => $formulario->createView(),
            ));
        }

        $this->setFlash('error', 'No se han podido realizar los siguientes cambios');
        return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
                    'entity' => $entity,
                    'formulario' => $formulario->createView(),
        ));
    }

    private function pagInicioValid($roles) {
        $data = $this->getRequest()->request->get('pac_usuariobundle_usuariotype');
        $pag_inicio = $this->getDoctrine()->getManager()->getRepository("UsuarioBundle:PaginaInicio")->find($data['pagina_inicio'])->getNombre();

        foreach ($roles as $rol) {
            if ($rol == "ROLE_$pag_inicio")
                return true;
        }
        return false;
    }

    /*public function deleteAction($id) {
        $this->get('session')->getFlashBag()->clear();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se pudo encontrar la entidad Usuario.');
        }

        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity->setUsuarioBaja($usuario);
        $entity->setFechaBaja(new \DateTime());
        $entity->setActivo(false);
        $entity->setEliminado(true);

        $em->persist($entity);
        $em->flush();

        $this->setFlash('success', 'El Usuario ' . $entity->getNombre() . ' fue desactivado con éxito.');

        return $this->__renderIndex();
    }*/

    public function restoreAction($id) {
        $this->get('session')->getFlashBag()->clear();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se pudo encontrar la entidad Usuario.');
        }

        $entity->setActivo(true);
        $entity->setEliminado(false);

        $em->persist($entity);
        $em->flush();

        $this->setFlash('success', 'El Usuario ' . $entity->getNombre() . ' fue activado con éxito.');

        return $this->__renderIndex();
    }

    public function showPerfilAction() {
        $this->get('session')->getFlashBag()->clear();
        $usuario = $this->get('security.context')->getToken()->getUser();

        return $this->render("UsuarioBundle:Usuario:showPerfil.html.twig", array(
                    'entity' => $usuario
        ));
    }

    public function editPerfilAction() {
        $this->get('session')->getFlashBag()->clear();

        $usuario = $this->get('security.context')->getToken()->getUser();

        $paginas_inicio = $this->getPaginasInicio($usuario->getPerfil());

        $formulario = $this->createForm(new MiPerfilType($paginas_inicio), $usuario);

        return $this->render('UsuarioBundle:Usuario:editPerfil.html.twig', array(
                    'entity' => $usuario,
                    'formulario' => $formulario->createView(),
        ));
    }

    public function updatePerfilAction() {
        $this->get('session')->getFlashBag()->clear();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $paginas_inicio = $this->getPaginasInicio($usuario->getPerfil());
        $formulario = $this->createForm(new MiPerfilType($paginas_inicio), $usuario);
        $peticion = $this->getRequest();
        $data = $peticion->request->get('pac_usuariobundle_miperfiltype');

        if ($peticion->getMethod() == 'POST') {
            $formulario->submit($peticion);
            if ($formulario->isValid()) {
                if (!$this->codIdentValid($data)) {
                    return $this->render('UsuarioBundle:Usuario:editPerfil.html.twig', array(
                                'usuario' => $usuario,
                                'formulario' => $formulario->createView()
                    ));
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $em->flush();
                $this->setFlash('success', 'Los datos de tu perfil se han actualizado correctamente'
                );
                return $this->render('UsuarioBundle:Usuario:editPerfil.html.twig', array(
                            'usuario' => $usuario,
                            'formulario' => $formulario->createView()
                ));
            }
        }
        $this->setFlash('error', 'No se han podido realizar los siguientes cambios.');
        return $this->render('UsuarioBundle:Usuario:editPerfil.html.twig', array(
                    'usuario' => $usuario,
                    'formulario' => $formulario->createView()
        ));
    }

    private function codIdentValid($data) {
        $tipo_doc = $data['tipo_documento'];
        $cod = $data['numero_documento'];
        if(!$cod){
            return true;
        }
        switch ($tipo_doc) {
            case "DNI":
            case "CI": {
                    $label = ($tipo_doc == "DNI") ? "El Número de Documento" : "La Cedula de Identidad";
                    if (strlen($cod) != 8 && strlen($cod) != 7) {
                        $this->setFlash('error', $label . ' debe contener 7 u 8 dígitos.');
                        return false;
                    }
                    if (!$this->int($cod)) {
                        $this->setFlash('error', $label . ' debe contener solo dígitos');
                        return false;
                    }
                    break;
                }
            case "LE":
            case "LC": {
                    $label = ($tipo_doc == "LE") ? "La Libreta de Enrolamiento" : "La Libreta Civica";
                    if (strlen($cod) != 7) {
                        $this->setFlash('error', $label . ' debe contener 8 dígitos.');
                        return false;
                    }
                    if (!$this->int($cod)) {
                        $this->setFlash('error', $label . ' debe contener solo dígitos');
                        return false;
                    }
                    break;
                }
            case "PA": {
                    if (strlen($cod) != 9) {
                        $this->setFlash('error', 'El Pasaporte debe contener 9 caracteres');
                        return false;
                    }
                    if (!preg_match("/^([A-Z]{3})\d{6}+$/", $cod) && !preg_match("/^\d{8}(C|F|M|N)+$/", $cod)) {
                        $this->setFlash('error', 'El pasaporte debe tener el formato con 3 caracteres en mayúscula y luego 6 dígitos o 
                        el formato con 8 dígitos y la letra C, F, M o N');
                        return false;
                    }
                    break;
                }
        }
        return true;
    }

    function int($int) {
        if (is_numeric($int) === TRUE) {
            if ((int) $int == $int) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function editPassPerfilAction() {
        $this->get('session')->getFlashBag()->clear();

        $formulario = $this->createForm(new MiPerfilPassType());

        return $this->render('UsuarioBundle:Usuario:editPassPerfil.html.twig', array(
                    'formulario' => $formulario->createView(),
        ));
    }

    public function updatePassPerfilAction() {
        $this->get('session')->getFlashBag()->clear();

        $em = $this->getDoctrine()->getManager();

        $usuario = $this->get('security.context')->getToken()->getUser();

        $formulario = $this->createForm(new MiPerfilPassType());

        $data = $this->getRequest()->request->get('pac_usuariobundle_myperfilpasstype');

        if (!$this->isPassValid($data, $usuario)) {
            return $this->render('UsuarioBundle:Usuario:editPassPerfil.html.twig', array(
                        'formulario' => $formulario->createView(),
            ));
        }
        $password = $data['password'];
        $encoder = $this->get('security.encoder_factory')
                ->getEncoder($usuario);
        $usuario->setSalt(md5(time()));
        $passwordCodificado = $encoder->encodePassword(
                $password['Contraseña'], $usuario->getSalt()
        );
        $usuario->setPassword($passwordCodificado);

        $em->persist($usuario);
        $em->flush();

        $this->setFlash('success', 'La contraseña se ha modificado correctamente.');

        return $this->render('UsuarioBundle:Usuario:showPerfil.html.twig', array(
                    'entity' => $usuario,
        ));
    }

    private function isPassValid($data, $usuario) {
        //Si tiene la contraseña anterior es que está editando con formulario de editar con contraseña actual. 
        if (array_key_exists('oldpassword', $data)) {
            if (!$data['oldpassword']) {
                $this->setFlash('error', 'Debe ingresar la contraseña Actual');
                return false;
            }

            $encoder = $this->get('security.encoder_factory')
                    ->getEncoder($usuario);
            $oldpassword_codificado = $encoder->encodePassword(
                    $data['oldpassword'], $usuario->getSalt()
            );
            if ($oldpassword_codificado != $usuario->getPassword()) {
                $this->setFlash('error', 'La contraseña Actual es incorrecta');
                return false;
            }
        }
        $password = $data['password'];
        if ($password['first'] == "" || $password['second'] == "") {
            $this->setFlash('error', 'Debe ingresar la nueva contraseña en los dos campos.');
            return false;
        }
        if (strcmp($password['first'], $password['second']) != 0) {
            $this->setFlash('error', 'Las contraseñas nuevas deben coincidir.');
            return false;
        }
        if (strlen($password['first']) < 8) {
            $this->setFlash('error', 'La contraseña debe tener minimo 8 caracteres');
            return false;
        }
        return true;
    }

    public function editPassAction($id) {
        $this->get('session')->getFlashBag()->clear();

        $em = $this->getDoctrine()->getManager();

        $usuario = $em->getRepository("UsuarioBundle:Usuario")->find($id);

        $form = $this->createForm(new UsuarioPassType());

        if (!$usuario) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Usuario.');
        }

        return $this->render("UsuarioBundle:Usuario:editPass.html.twig", array(
                    'entity' => $usuario,
                    'formulario' => $form->createView(),
        ));
    }

    public function updatePassAction($id) {
        $this->get('session')->getFlashBag()->clear();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha podido encontrar la entidad Usuario.');
        }

        $formulario = $this->createForm(new UsuarioPassType());

        $data = $this->getRequest()->request->get('pac_usuariobundle_usuariopasstype');

        $password = $data['password'];
        if (!$this->isPassValid($data, $entity)) {
            return $this->render('UsuarioBundle:Usuario:editPass.html.twig', array(
                        'entity' => $entity,
                        'formulario' => $formulario->createView(),
            ));
        }
        $password_ori = $password['first'];
        $encoder = $this->get('security.encoder_factory')
                ->getEncoder($entity);
        $entity->setSalt(md5(time()));
        if (!isset($password['Contraseña'])){
            $passwordCodificado = $encoder->encodePassword(
                    $password['first'], $entity->getSalt()
            );
        }else{
            $passwordCodificado = $encoder->encodePassword(
                    $password['Contraseña'], $entity->getSalt()
            );
        }
        $entity->setPassword($passwordCodificado);

        if (isset($data['enviar_mail']) && $data['enviar_mail'] && $data['enviar_mail']) {
            $this->__enviarMail(
                    array(
                        'from' => $this->container->getParameter('mailer_user'),
                        'to' => $entity->getEmail(),
                        'subject' => 'Cambio de Contraseña.',
                        'body' => 'Su nueva contraseña es ' . $password_ori. '.<br>'
            ));
        }

        $em->persist($entity);
        $em->flush();

        $this->setFlash('success', 'La contraseña se ha modificado correctamente.');

        return $this->render('UsuarioBundle:Usuario:show.html.twig', array(
                    'entity' => $entity,
                    'formulario' => $formulario->createView(),
        ));
    }

    public function inboxAction() {
        //$em = $this->getDoctrine()->getManager();
        //$usuario = $this->get('security.context')->getToken()->getUser();
        return $this->render('UsuarioBundle:Usuario:inbox.html.twig', array(
                        //'entities' => $entities
        ));
    }

    public function outboxAction() {
        //$em = $this->getDoctrine()->getManager();
        //$usuario = $this->get('security.context')->getToken()->getUser();
        return $this->render('UsuarioBundle:Usuario:outbox.html.twig', array(
                        //'entities' => $entities
        ));
    }

    public function escribirAction() {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity = new \APP\UsuarioBundle\Entity\Mensaje();
        $form = $this->createForm(new \APP\UsuarioBundle\Form\MensajeType(), $entity);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->submit($request);
            if ($form->isValid()) {
                $entity->setUsuarioOrigen($usuario);
                $entity->setLeido(false);
                $em->persist($entity);
                $em->flush();
                return $this->render('UsuarioBundle:Usuario:outbox.html.twig', array(
                                //'entities' => $entities
                ));
            } else {
                return $this->render('UsuarioBundle:Usuario:escribir.html.twig', array(
                            'form' => $form->createView()
                ));
            }
        }
        return $this->render('UsuarioBundle:Usuario:escribir.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function leerAction($id){
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity = $em->getRepository('UsuarioBundle:Mensaje')->findOneById($id);
        if (!$entity){
            throw $this->createNotFoundException('No se ha podido encontrar el mensaje.');
        }else{
            $entity->setLeido(true);
            $em->persist($entity);
        }
        $em->flush();
        return $this->render('UsuarioBundle:Usuario:leer.html.twig', array(
                    'mensaje' => $entity
        ));
    }


    private function __enviarMail($mail) {
        $host = $this->getRequest()->getHost();
        $explode = explode('.', $host);
        $end = end($explode);
        $end = "enviar";
        if ($end != "localhost") {
            try {
                try {
                    $message = \Swift_Message::newInstance()
                            ->setSubject($mail['subject'])
                            ->setFrom($mail['from'])
                            ->setTo($mail['to'])
                            ->setBody($mail['body'], "text/html")
                    ;
                    if (!$this->get('mailer')->send($message, $errores)) {
                        //print_r("no se envio mail a guardar en log");
                        $logger = $this->get('logger');
                        $logger->err($errores);
                    }
                } catch (\Swift_TransportException $e) {
                    $logger = $this->get('logger');
                    $logger->err($e->getMessage());
                }
            } catch (\Swift_IoException $e) {
                $logger = $this->get('logger');
                $logger->err($e->getMessage());
            }
        } else {
            $this->setFlash('error', "No se envio mail. " . $mail['body']);
        }
    }

    private function setFlash($index, $message) {
        $this->get('session')->getFlashBag()->clear();
        $this->get('session')->getFlashBag()->add($index, $message);
    }
    
    /**
     * Elimina la entidad Proyecto modificando el campo eliminado.
     *
     */
    public function eliminarAction()
    {
        /* @var $entity \APP\UsuarioBundle\Entity\Usuario */
        $request = $this->getRequest();
        if ($request->getMethod() == "POST") {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository("UsuarioBundle:Usuario")->find($id);
            if (!$entity) throw $this->createNotFoundException('No se ha podido encontrar la entidad.');
            $entity->setFechaActualizacion(new \DateTime());
            $usuario = $this->get('security.context')->getToken()->getUser();
            $entity->setEliminado(true);
            $em->persist($entity);
            $em->flush();
        }
        return new Response("");
    }

    /**
     * Elimina la entidad Proyecto modificando el campo eliminado.
     *
     */
    public function restaurarAction()
    {
        $request = $this->getRequest();
        if ($request->getMethod() == "POST") {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository("UsuarioBundle:Usuario")->find($id);
            if (!$entity) throw $this->createNotFoundException('No se ha podido encontrar la entidad.');
            $entity->setFechaActualizacion(new \DateTime());
            $usuario = $this->get('security.context')->getToken()->getUser();
            $entity->setEliminado(false);
            $em->persist($entity);
            $em->flush();
        }
        

        return new Response("");
    }
    /**
     * Deletes a Proyecto entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    if ($request->getMethod() == "POST") {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UsuarioBundle:Usuario')->find($id);
        if (!$entity) throw $this->createNotFoundException('Unable to find Proyecto entity.');

        $usuario = $this->get('security.context')->getToken()->getUser();
        $entity->setEliminado(true);
        $em->persist($entity);
        $em->flush();
        $this->setFlash('info','La eliminación se ha realizado de manera correcta');
    }

        return $this->redirect($this->generateUrl('proyecto'));
    }

    private function bodyMailAltaUsuario($username,$password) {
        $sistema = 'AgendaSSTG';
        return 'Se le ha creado un usuario nuevo en el sistema '.$sistema.'.<br /><br />'
            . 'El nombre del usuario con el que tendrá acceso es \'' .
            $username . '\' y su contraseña es \'' . $password . '\' (Sin comillas).<br /><br />' . 
            'Para poder acceder al sistema deberá hacer click en el siguiente link: ' .
            '<a href="http://' . $this->getRequest()->getHost() . '"> http://' . $this->getRequest()->getHost() . '</a><br /><br />' . 
            'Desde ya, muchas gracias.';
    }
    
}
