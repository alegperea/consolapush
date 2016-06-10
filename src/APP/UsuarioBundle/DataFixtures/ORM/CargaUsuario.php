<?php

namespace APP\UsuarioBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use APP\UsuarioBundle\Entity\Usuario;
use APP\UsuarioBundle\Entity\Rol;
use APP\UsuarioBundle\Entity\Perfil;
use APP\UsuarioBundle\Entity\PaginaInicio;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Description of usuario
 *
 * @author Diego
 */ 
class CargaUsuario implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {            
        $pag_inicio = new PaginaInicio();
        $pag_inicio->setNombre('PROYECTOS');
        $pag_inicio->setRuta('proyectos');
        $manager->persist($pag_inicio);
        $userAdmin = new Usuario();
        $userAdmin->setUsername('admin');
        $userAdmin->setNombre('Usuario');
        $userAdmin->setApellido('Administrador');
        $userAdmin->setCalle("calle");
        $userAdmin->setEmail('admin@localhost.local');
        $userAdmin->setActivo(true);
        $userAdmin->setFechaActualizacion(new \DateTime());
        $userAdmin->setPaginaInicio($pag_inicio);
        $userAdmin->setUsuarioAlta($userAdmin);
            $userAdmin->setSalt(md5(time()));
            $passwordEnClaro = 'password';
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($userAdmin);
            $passwordCodificado = $encoder->encodePassword($passwordEnClaro, $userAdmin->getSalt());
            $userAdmin->setPassword($passwordCodificado);
        $manager->persist($userAdmin);
        
        
        $manager->persist($pag_inicio);
        $user = new Usuario();
        $user->setUsername('user');
        $user->setNombre('Usuario');
        $user->setApellido('Visualizador');
        $user->setEmail('user@localhost.local');
        $user->setActivo(true);
        $user->setFechaActualizacion(new \DateTime());
        $user->setPaginaInicio($pag_inicio);
        $user->setUsuarioAlta($user);
            $user->setSalt(md5(time()));
            $passwordEnClaro = 'password';
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $passwordCodificado = $encoder->encodePassword($passwordEnClaro, $user->getSalt());
            $user->setPassword($passwordCodificado);
        $manager->persist($user);
        
        $manager->persist($pag_inicio);
        $gestor = new Usuario();
        $gestor->setUsername('gestor');
        $gestor->setNombre('Usuario');
        $gestor->setApellido('Gestor');
        $gestor->setEmail('gestor@localhost.local');
        $gestor->setActivo(true);
        $gestor->setFechaActualizacion(new \DateTime());
        $gestor->setPaginaInicio($pag_inicio);
        $gestor->setUsuarioAlta($gestor);
            $gestor->setSalt(md5(time()));
            $passwordEnClaro = 'password';
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($gestor);
            $passwordCodificado = $encoder->encodePassword($passwordEnClaro, $gestor->getSalt());
            $gestor->setPassword($passwordCodificado);
        $manager->persist($gestor);
        
        // CARGA PERFILES //
        $perfilAdministrador = new Perfil();
        $perfilAdministrador->setActivo(true);
        $perfilAdministrador->setDescripcion('Perfil de usuario Administrador');
        $perfilAdministrador->setFechaAlta(new \DateTime());
        $perfilAdministrador->setUsuarioAlta($userAdmin);
        $perfilAdministrador->setNombre('Administrador');

        // CARGA PERFILES //
        $perfilGestor = new Perfil();
        $perfilGestor->setActivo(true);
        $perfilGestor->setDescripcion('Perfil Gestor');
        $perfilGestor->setFechaAlta(new \DateTime());
        $perfilGestor->setUsuarioAlta($userAdmin);
        $perfilGestor->setNombre('Gestor');
        
        // CARGA PERFILES //
        $perfilUsuario = new Perfil();
        $perfilUsuario->setActivo(true);
        $perfilUsuario->setDescripcion('Perfil Visualizador');
        $perfilUsuario->setFechaAlta(new \DateTime());
        $perfilUsuario->setUsuarioAlta($userAdmin);
        $perfilUsuario->setNombre('Visualizador');
        
        // CARGA ROLES //
        $rolUsuario = new Rol();
        $rolUsuario->setNombre("ROLE_USUARIO");
        $rolUsuario->setDescripcion('Rol de pantalla de inicio de administración');
        $rolUsuario->setUsuarioAlta($userAdmin);
        $manager->persist($rolUsuario);
        
        $rolAdmin = new Rol();
        $rolAdmin->setNombre("ROLE_ADMINISTRADOR");
        $rolAdmin->setDescripcion('Rol de administrador');
        $rolAdmin->setUsuarioAlta($userAdmin);
        $manager->persist($rolAdmin);

        $rolGestor = new Rol();
        $rolGestor->setNombre("ROLE_GESTOR");
        $rolGestor->setDescripcion('Rol de gestor');
        $rolGestor->setUsuarioAlta($userAdmin);
        $manager->persist($rolGestor);
        
        $rolConfig = new Rol();
        $rolConfig->setNombre("ROLE_CONFIGURACION");
        $rolConfig->setDescripcion('Rol de configuracion');
        $rolConfig->setUsuarioAlta($userAdmin);
        $manager->persist($rolConfig);
        
        // ASIGNACION DE ROLES A PERFILES //
        $perfilAdministrador->addRole($rolUsuario);
        $perfilAdministrador->addRole($rolAdmin);
        $perfilAdministrador->addRole($rolConfig);
        $perfilAdministrador->addRole($rolGestor);
        $perfilAdministrador->setPaginaInicioDefault($pag_inicio);
        
        $perfilUsuario->addRole($rolUsuario);
        $perfilUsuario->setPaginaInicioDefault($pag_inicio);
        
        $perfilGestor->addRole($rolUsuario);
        $perfilGestor->addRole($rolGestor);
        
        
        $manager->persist($perfilAdministrador);
        $manager->persist($perfilUsuario);
        $manager->persist($perfilGestor);
        
        $userAdmin->setPerfil($perfilAdministrador);
        $user->setPerfil($perfilUsuario);
        $gestor->setPerfil($perfilGestor);

        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }
}

?>
