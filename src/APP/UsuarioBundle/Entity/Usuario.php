<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;;
use APP\UsuarioBundle\Entity\Perfil;
use APP\UsuarioBundle\Entity\Rol;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * APP\UsuarioBundle\Entity\Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="APP\UsuarioBundle\Entity\UsuarioRepository")
 * @DoctrineAssert\UniqueEntity("username")
 * @DoctrineAssert\UniqueEntity("email")
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    const EMPLEADOADMINISTRADOR = 1;
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
     * @ORM\Column(name="nombre", type="string", length=40, nullable=false)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     * @Assert\Length(
     *     max=40,
     *     maxMessage="El campo debe tener máximo {{ limit }} caracteres.",
     *     min=3,
     *     minMessage="El campo debe tener mínimo {{ limit }} caracteres.",
     *     groups={"editmyperfil","registro"}
     * )     
     */
    private $nombre;

    /**
     * @var string $apellido
     *
     * @ORM\Column(name="apellido", type="string", length=40, nullable=false)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     * @Assert\Length(
     *     max=40,
     *     maxMessage="El campo debe tener máximo {{ limit }} caracteres.",
     *     min=3,
     *     minMessage="El campo debe tener mínimo {{ limit }} caracteres.",
     *     groups={"editmyperfil","registro"}
     * )     
     */
    private $apellido;

    /**
     * @var string $tipo_documento
     *
     * @ORM\Column(name="tipo_documento", type="string", length=20, nullable=true)
     */
    private $tipo_documento;

    /**
     * @var string $numero_documento
     *
     * @ORM\Column(name="numero_documento", type="string", length=12, nullable=true)
     * @Assert\Length(
     *      min=7,
     *      minMessage="El número de documento debe tener mínimo {{ limit }} números",
     *      max=9,
     *      maxMessage="El número de documento debe tener máximo {{ limit }} números"
     * )
     */
    private $numero_documento;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=255,  nullable=false, unique=true)
     * @Assert\Length(
     *     max=255,
     *     maxMessage="El campo debe tener máximo {{ limit }} caracteres.",
     *     min=3,
     *     minMessage="El campo debe tener mínimo {{ limit }} caracteres."
     * )     
     */
    private $username;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     * @Assert\Email(message = "El mail '{{ value }}' ingresado no tiene el formato correcto.")
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="El campo no puede ser vacio o tener unicamente blancos")
     * @Assert\Length(
     *      min=8,
     *      minMessage="La contraseña debe tener minimo {{ limit }} caracteres.",
     *      groups={"pass"}
     * )
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=50)
     */
    private $salt;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float",nullable=true)
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lon", type="float",nullable=true)
     */
    private $lon;

    /**
     * @var boolean $activo
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @var DateTime $fecha_actualizacion
     * 
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fecha_actualizacion;

    /**
     * @var integer $usuario_actualizacion
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Usuario")
     */
    private $usuarioActualizacion;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=255,nullable=true)
     * @Assert\Regex(pattern="/^([1-9][0-9]{7}|[1-9][0-9]{9}|\([0-9]+\)[1-9][0-9]{7}|\([0-9]+\)[1-9][0-9]{9})$/", message="El telefono debe tener el siguiente formato 12345678 o 1512345678 o (54)1112345678",groups={"editmyperfil"})
     * @Assert\Length(min=6,groups={"editmyperfil"})
     */
    private $telefono;

    /**
     * @var string $telefonoAlternativo
     *
     * @ORM\Column(name="telefono_alternativo", type="string", length=255,nullable=true)
     * @Assert\Regex(pattern="/^([1-9][0-9]{7}|[1-9][0-9]{9}|\([0-9]+\)[1-9][0-9]{7}|\([0-9]+\)[1-9][0-9]{9})$/", message="El telefono alternativo debe tener el siguiente formato 12345678 o 1512345678 o (54)1112345678",groups={"editmyperfil"})
     * @Assert\Length(min=6,groups={"editmyperfil"})
     */
    private $telefonoAlternativo;

    /**
     * @var string $interno
     *
     * @ORM\Column(name="interno", type="string", length=10, nullable=true)
     */
    private $interno;

    /**
     * @var string $oficina
     *
     * @ORM\Column(name="oficina", type="string", length=255,nullable=true)
     */
    private $oficina;

    /**
     * @var string $calle
     *
     * @ORM\Column(name="calle", type="string", length=255,nullable=true)
     */
    private $calle;
     
    
    
    /**
     * @var integer $direccionNro
     *
     * @ORM\Column(name="direccion_nro", type="integer",nullable=true)
     */
    private $direccionNro;
    
    /**
     * @var string $codigoPostal
     * 
     * @ORM\Column(name="codigo_postal", type="string", length=50,nullable=true)
     */
    private $codigoPostal;

    /**
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\Perfil")
     */
    private $perfil;
    
    /**
     * @var boolean $eliminado
     *
     * @ORM\Column(name="eliminado", type="boolean")
     */
    private $eliminado;

    /**
     * @var string $pagina_inicio
     *
     * @ORM\ManyToOne(targetEntity="APP\UsuarioBundle\Entity\PaginaInicio")
     */
    private $pagina_inicio;
    
    /**
     * @ORM\OneToMany(targetEntity="APP\UsuarioBundle\Entity\Mensaje", mappedBy="usuario_origen")
     */
    private $mensajes_enviados;
    
    /**
     * @ORM\OneToMany(targetEntity="APP\UsuarioBundle\Entity\Mensaje", mappedBy="usuario_destino")
     */
    private $mensajes_recibidos;
    
    /**
     * @Assert\Image(maxSize = "500k")
     */
    private $foto;
    
    /**
     * @var string $ruta_foto
     * 
     * @ORM\Column(name="ruta_foto", type="string", length=255,nullable=true)
     */
    private $rutaFoto;
    
    
    public function __construct()
    {
        $this->activo = true;
        $this->fecha_actualizacion = new \DateTime();
        $this->eliminado = false;
        $this->mensajes_enviados = new ArrayCollection();
        $this->mensajes_recibidos = new ArrayCollection();
        $this->rutaFoto = "images.jpeg";
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
     * Set apellido
     *
     * @param string $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set tipo_documento
     *
     * @param string $tipoDocumento
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipo_documento = $tipoDocumento;
    }

    /**
     * Get tipo_documento
     *
     * @return string 
     */
    public function getTipoDocumento()
    {
        return $this->tipo_documento;
    }

    /**
     * Set numero_documento
     *
     * @param string $numeroDocumento
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numero_documento = $numeroDocumento;
    }

    /**
     * Get numero_documento
     *
     * @return string 
     */
    public function getNumeroDocumento()
    {
        return $this->numero_documento;
    }
    
    public function getDocumento(){
        return $this->tipo_documento . ": " . $this->numero_documento;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
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
     * Set eliminado
     *
     * @param boolean $eliminado
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;
    }

    /**
     * Get eliminado
     *
     * @return boolean 
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }
    
    public function eliminado()
    {
        return $this->eliminado;
    }

    /**
     * Set fecha_actualizacion
     *
     * @param datetime $fecha
     */
    public function setFechaActualizacion($fecha = null) {
        $this->fechaActualizacion = $fecha == null ? new \DateTime() : $fecha;
    }

    /**
     * Get fecha_actualizacion
     *
     * @return datetime 
     */
    public function getFechaActualizacion()
    {
        return $this->fecha_actualizacion;
    }

    /**
     * Set usuario_alta
     *
     * @param integer $usuarioAlta
     */
    public function setUsuarioAlta($usuarioAlta)
    {
        $this->usuario_actualizacion = $usuarioAlta;
    }

    /**
     * Get usuario_alta
     *
     * @return integer 
     */
    public function getUsuarioAlta()
    {
        return $this->usuario_actualizacion;
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
     * Set telefono
     *
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set interno
     *
     * @param string $interno
     */
    public function setInterno($interno)
    {
        $this->interno = $interno;
    }

    /**
     * Get interno
     *
     * @return string 
     */
    public function getInterno()
    {
        return $this->interno;
    }

    /**
     * Set oficina
     *
     * @param string $oficina
     */
    public function setOficina($oficina)
    {
        $this->oficina = $oficina;
    }

    /**
     * Get oficina
     *
     * @return string 
     */
    public function getOficina()
    {
        return $this->oficina;
    }

    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
    }
    
    public function getPerfil()
    {
        return $this->perfil;
    }

    public function getArea()
    {
        return $this->area;
    }
    
    public function equals(\Symfony\Component\Security\Core\User\UserInterface $usuario)
    {
        return $this->getUsername() == $usuario->getUsername();
    }
    
    public function eraseCredentials()
    {
    }
    
    public function getRoles()
    {
        return $this->getPerfil()->getRolesArray();
    }
    
    public function serialize()
    {
        return serialize(array($this->id,$this->username));
    }

    public function unserialize($data)
    {
        list($this->id,$this->username) = unserialize($data);
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }
    
    public function isEnabled()
    {
        return !$this->eliminado;
    }
    
    public function isAccountNonLocked() {
        return !$this->eliminado;
    }

    public function isCredentialsNonExpired() {
        return true;
    }
    
    public function isPerfilAdministrador() {
        return $this->getPerfil()->getId() == Perfil::ADMINISTRADOR;
    }
    
    public function isPerfilUsuario() {
        return $this->getPerfil()->getId() == Perfil::EMPLEADO;
    }
    
    public function setPaginaInicio($pag){
        $this->pagina_inicio = $pag;
    }
    
    public function getPaginaInicio(){
        return $this->pagina_inicio;
    }
    
    public function getRutaFoto() {
        return $this->rutaFoto;
    }

    public function setRutaFoto($rutaFoto) {
        $this->rutaFoto = $rutaFoto;
    }
        
   /**
    * @param UploadedFile $foto
    */
    public function setFoto(UploadedFile $foto = null)
    {
         $this->foto = $foto;
    }
    
   /**
    * @return UploadedFile
    */
    public function getFoto()
    {
        return $this->foto;
    }
    
    public function subirFoto($directorioDestino)
    {
        if (null === $this->foto) {
            return;
        }
        $nombreArchivoFoto = uniqid('foto-').'-perfil.jpg';
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        $this->setRutaFoto($nombreArchivoFoto);
    }
    
    public function getMensajesEnviados() {
        return $this->mensajes_enviados;
    }

    public function setMensajesEnviados($mensajes_enviados) {
        $this->mensajes_enviados = $mensajes_enviados;
    }

    public function getMensajesRecibidos() {
        return $this->mensajes_recibidos;
    }

    public function setMensajesRecibidos($mensajes_recibidos) {
        $this->mensajes_recibidos = $mensajes_recibidos;
    }

    public function getTelefonoAlternativo() {
        return $this->telefonoAlternativo;
    }

    public function setTelefonoAlternativo($telefonoAlternativo) {
        $this->telefonoAlternativo = $telefonoAlternativo;
    }

    public function getCalle() {
        return $this->calle;
    }

    public function setCalle($calle) {
        $this->calle = $calle;
    }

    public function getDireccionNro() {
        return $this->direccionNro;
    }

    public function setDireccionNro($direccionNro) {
        $this->direccionNro = $direccionNro;
    }

    public function getCodigoPostal() {
        return $this->codigoPostal;
    }


    public function setCodigoPostal($codigoPostal) {
        $this->codigoPostal = $codigoPostal;
    }
   
    public function __toString()
    {
        return $this->getNombre().' '.$this->getApellido();
    }
    
    public function getMensajesNoLeidos(){
        $collection = new ArrayCollection();
        foreach ($this->mensajes_recibidos as $mensaje){
            if ($mensaje->getLeido()==false){
                $collection->add($mensaje);
            }
        }
        return ($collection);
    }
    
    public function getMensajesLeidos(){
        $collection = new ArrayCollection();
        foreach ($this->mensajes_recibidos as $mensaje){
            if ($mensaje->getLeido()==true){
                $collection->add($mensaje);
            }
        }
        return ($collection);
    }
    
}

