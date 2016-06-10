<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use APP\UsuarioBundle\Entity\Usuario;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Mensaje
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="APP\UsuarioBundle\Entity\MensajeRepository")
 */
class Mensaje
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * 
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="leido", type="boolean")
     */
    private $leido;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Usuario", inversedBy="mensajes_enviados")
     * @ORM\JoinColumn(name="usuario_origen_id", referencedColumnName="id", nullable=false)
     */
    private $usuario_origen;
    
    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Usuario", inversedBy="mensajes_recibidos")
     * @ORM\JoinColumn(name="usuario_destino_id", referencedColumnName="id", nullable=false)
     */
    private $usuario_destino;
    

    /**
     * @ORM\OneToOne(targetEntity="APP\UsuarioBundle\Entity\Mensaje", cascade={"persist"})
     * @ORM\JoinColumn(name="mensaje_respuesta_id", referencedColumnName="id", nullable=true)
     */
    private $mensaje_respuesta;
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
     * Set texto
     *
     * @param string $texto
     * @return Mensaje
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    
        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Mensaje
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Mensaje
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set leido
     *
     * @param boolean $leido
     * @return Mensaje
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;
    
        return $this;
    }

    /**
     * Get leido
     *
     * @return boolean 
     */
    public function getLeido()
    {
        return $this->leido;
    }
    
    public function getUsuarioOrigen() {
        return $this->usuario_origen;
    }

    public function setUsuarioOrigen(Usuario $usuario_origen) {
        $this->usuario_origen = $usuario_origen;
    }

    public function getUsuarioDestino() {
        return $this->usuario_destino;
    }

    public function setUsuarioDestino(Usuario $usuario_destino) {
        $this->usuario_destino = $usuario_destino;
    }

    public function getMensajeRespuesta() {
        return $this->mensaje_respuesta;
    }

    public function setMensajeRespuesta($mensaje_respuesta) {
        $this->mensaje_respuesta = $mensaje_respuesta;
    }


}
