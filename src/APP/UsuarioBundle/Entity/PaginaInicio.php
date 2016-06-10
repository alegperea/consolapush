<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * APP\UsuarioBundle\Entity\PaginaInicio
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PaginaInicio
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
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;
   
    /**
     * @var string $ruta
     *
     * @ORM\Column(name="ruta", type="string", length=50)
     */
    private $ruta;

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
     * Set ruta
     *
     * @param string $ruta
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }
    
    public function __toString(){
        return strtoupper($this->nombre);
    }
}