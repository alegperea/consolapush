<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * APP\UsuarioBundle\Entity\Rol
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="APP\UsuarioBundle\Entity\RolRepository")
 * @DoctrineAssert\UniqueEntity("nombre");
 */
class Rol
{
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
     * @ORM\Column(name="nombre", type="string", length=255,unique=true)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     */
    private $nombre;

    /**
     * @var text $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
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
     * @var integer $usuario_alta
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Usuario")
     */
    private $usuario_alta;

    /**
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
     * @var boolean $activo
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @ORM\ManyToMany(targetEntity="APP\UsuarioBundle\Entity\Perfil", mappedBy="roles")
     */
    private $perfiles;


    public function __construct()
    {
        $this->fecha_alta = new \DateTime();
        $this->perfiles = new ArrayCollection();
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
     * @param text $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get descripcion
     *
     * @return text 
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
     * Set fecha_baja
     *
     */
    public function setFechaBaja($fechaBaja)
    {
        $this->fecha_baja = $fechaBaja;
    }

    /**
     * Get fecha_baja
     *
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
     * Set perfil
     *
     */
    public function setPerfiles($perfiles)
    {
        $this->perfil = $perfiles;
    }

    /**
     * Get perfil
     */
    public function getPerfiles()
    {
        return $this->perfiles;
    }
    
    public function __toString()
    {
        return $this->getNombre();
    }
}