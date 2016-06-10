<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;

class MiPerfilType extends AbstractType
{
    private $paginas_inicio;
    
    public function __construct($paginas_inicio = "") {
        $this->paginas_inicio = $paginas_inicio;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('tipo_documento','choice', array(
                        'choices'   => array(
                            'DNI' => 'DNI',
                            'CI' => 'CI',
                            'PA' => 'PASAPORTE',
                            'LE' => 'LIBRETA DE ENROLAMIENTO',
                            'LC' => 'LIBRETA CIVICA',
                        )
            ))
            ->add('numero_documento')
            //->add('username')
            ->add('email', 'email')
            /*->add('password', 'repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'Las dos contraseñas deben coincidir',
                        'first_name' => 'Contraseña',
                        'second_name' => 'Repetir Contraseña'
            ))*/
            //->add('activo')
            ->add('telefono')
            ->add('interno')
            ->add('oficina')
            ->add('direccion');
            if (strlen($this->paginas_inicio) > 0){
                $paginas_inicio = $this->paginas_inicio;
                $builder
                    ->add('pagina_inicio', 'entity', array(
                        'class' => 'UsuarioBundle:PaginaInicio',
                        'query_builder' => function(EntityRepository $er) use ($paginas_inicio){
                            return $er->createQueryBuilder('u')
                                ->where("u.nombre in $paginas_inicio")
                                ->orderBy('u.nombre', 'ASC')
                                ;
                        },
                    ));
            }
            //->add('area')
            //->add('perfil')
        ;
    }

    public function getName()
    {
        return 'pac_usuariobundle_miperfiltype';
    }
}
