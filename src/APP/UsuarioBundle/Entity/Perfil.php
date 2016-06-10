<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * APP\UsuarioBundle\Entity\Perfil
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="APP\UsuarioBundle\Entity\PerfilRepository")
 * @DoctrineAssert\UniqueEntity("nombre");
 */
class Perfil
{
    const ADMINISTRADOR = 1;
    const USUARIO = 2;
    const GESTOR = 3;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id; 

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     */
    private $nombre;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     */
    private $descripcion;

    /**
     * @var datetime $fecha_alta
     *
     * @ORM\Column(name="fecha_alta", type="datetime")
     */
    private $fecha_alta;

    /**
     * @var datetime $fecha_baja
     *
     * @ORM\Column(name="fecha_baja", type="datetime", nullable=true)
     */
    private $fecha_baja;

    /**
     * @var integer $usuario_baja
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_baja_id", referencedColumnName="id", nullable=true) 
     */
    private $usuario_baja;

    /**
     * @var integer $usuario_alta
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Usuario")
     */
    private $usuario_alta;

    /**
     * @var boolean $activo
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @var integer $roles
     *
     * @ORM\ManyToMany(targetEntity="APP\UsuarioBundle\Entity\Rol", inversedBy="perfiles", cascade={"persist"})
     */
    private $roles;
    
    /**
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\PaginaInicio")
     */
    private $pagina_inicio_default;
    
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->activo = true;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fecha_alta
     *
     * @param datetime $fechaAlta
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fecha_alta = $fechaAlta;
    }

    /**
     * Get fecha_alta
     *
     * @return datetime 
     */
    public function getFechaAlta()
    {
        return $this->fecha_alta;
    }

    /**
     * Set fecha_baja
     *
     * @param datetime $fechaBaja
     */
    public function setFechaBaja($fechaBaja)
    {
        $this->fecha_baja = $fechaBaja;
    }

    /**
     * Get fecha_baja
     *
     * @return datetime 
     */
    public function getFechaBaja()
    {
        return $this->fecha_baja;
    }

    /**
     * Set usuario_baja
     *
     * @param integer $usuarioBaja
     */
    public function setUsuarioBaja($usuarioBaja)
    {
        $this->usuario_baja = $usuarioBaja;
    }

    /**
     * Get usuario_baja
     *
     * @return integer 
     */
    public function getUsuarioBaja()
    {
        return $this->usuario_baja;
    }

    /**
     * Set usuario_alta
     *
     * @param integer $usuarioAlta
     */
    public function setUsuarioAlta($usuarioAlta)
    {
        $this->usuario_alta = $usuarioAlta;
    }

    /**
     * Get usuario_alta
     *
     * @return integer 
     */
    public function getUsuarioAlta()
    {
        return $this->usuario_alta;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set roles
     *
     * @param integer $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
    
    public function getPaginaInicioDefault()
    {
        return $this->pagina_inicio_default;
    }
    
    public function setPaginaInicioDefault($pagina_inicio_default)
    {
        $this->pagina_inicio_default = $pagina_inicio_default;
    }

    /**
     * Get roles
     *
     * @return integer 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function addRole($rol)
    {
        $this->roles->add($rol);
    }
    
    public function getRolesArray()
    {
       $array[] = array();
       for ( $i = 0 ; $i < $this->roles->count() ; $i++ )
       {
           $rol = $this->roles->get($i);
           $array[$i]=$rol->getNombre();
       }
       return $array;
    }
    
    public function __toString()
    {
        return $this->getNombre();
    }
    
    public function tieneRol($rol)
    {
       $i=0;$existe=false;
       for ($i;$i<$this->roles->count()&&!$existe;$i++)
       {
           if ($rol == $this->roles->get($i))
           {
               $existe=true;
           }
       }    
    return $existe;
    }
    
    public function esRolConfiguracion()
    {
        foreach ( $this->roles as $rol )
        {
            if ( $rol->getNombre() == "ROLE_CONFIGURACION")
                return true;
        }
        return false;
    }
    
    public function isPerfilDefault()
    {
        return ($this->getId() == 1
                || $this->getId() == 2
                || $this->getId() == 3
                || $this->getId() == 4
                || $this->getId() == 5
                || $this->getId() == 6
                );
    }
}
