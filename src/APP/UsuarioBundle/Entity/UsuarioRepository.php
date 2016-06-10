<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\EntityRepository;

use APP\UsuarioBundle\Entity\Usuario;
use APP\UsuarioBundle\Entity\Perfil;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends EntityRepository
{
    public function findAll() {
        return $this->getEntityManager()
                ->createQuery("SELECT e
                    FROM UsuarioBundle:Usuario e")
                ->getResult();
    }
    
    public function findByEliminado($eliminado) {
        return $this->getEntityManager()
                ->createQuery("SELECT e 
                    FROM UsuarioBundle:Usuario e 
                    WHERE e.eliminado = :eliminado")
                ->setParameter('eliminado', $eliminado)
                ->getResult();
    }
    
    public function findByActivos() {
        return $this->getEntityManager()
                ->createQuery("SELECT e 
                    FROM UsuarioBundle:Usuario e 
                    WHERE e.eliminado = :eliminado")
                ->setParameter('eliminado', false)
                ->getResult();
    }

    public function findOneByNombreApellido($nombreApellido)
    {
        list($nombre,$apellido) = explode(" ",$nombreApellido);
        $consulta = $this->getEntityManager()
            ->createQuery('SELECT u 
                FROM UsuarioBundle:Usuario u
                WHERE u.nombre = :nombre
                AND u.apellido = :apellido
                AND u.eliminado = false')
            ->setParameters(array(
                'nombre' => $nombre,
                'apellido' => $apellido,
            ))
            ->setMaxResults(1)
            ;
            try {
                return $consulta->getSingleResult();
            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }
        ;
    }
    
    public function findPaginasInicio($paginas_inicio)
    {
        return $this->getEntityManager()
                ->createQuery("SELECT p
                    FROM UsuarioBundle:PaginaInicio p
                    WHERE p.nombre in $paginas_inicio")
                ->getResult()
                ;
    }
    
    public function updUsuariosBloquados($fecha)
    {
        $consulta = $this->getEntityManager()
                ->createQueryBuilder()
                ->update('UsuarioBundle:Usuario','e')
                ->set('e.estado', \APP\CoreBundle\Entity\EstadoEncargado::OFFLINE)
                ->where('e.estado = :estado')
                ->andWhere('e.fechaActualizacionServer < :fecha')
                ->setParameter('estado', \APP\CoreBundle\Entity\EstadoEncargado::ONLINE)
                ->setParameter('fecha', $fecha)
                ->getQuery();
        try {
            return $consulta->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
    public function findByLikeNombre($nombre)
    {
        return $this->getEntityManager()
                ->createQuery("SELECT e"
                        . " FROM UsuarioBundle:Usuario e"
                        . " WHERE lower(e.nombre) like lower(:nombre)")
                ->setParameter('nombre', "%".$nombre."%")
                ->getResult();
    }
    
    
}