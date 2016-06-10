<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class UsuarioType extends AbstractType {

    private $password;
    private $paginas_inicio;
    private $usuario;

    public function __construct( $user, $paginas_inicio = "") {
        $this->paginas_inicio = $paginas_inicio;
        $this->password = strlen($paginas_inicio) == 0;
        $this->usuario = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($this->password) {
            $builder
                    ->add('username', 'text', array(
                        'attr' => array(
                            'class' => 'input-xlarge',
                            'placeholder' => 'Nombre de Usuario'
                        )
                    ))
                    ->add('password', 'repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'Las dos contraseñas deben coincidir',
                        'first_options' => array('label' => 'Contraseña', 'attr' => array(
                                'class' => 'input-xlarge',
                            )),
                        'second_options' => array('label' => 'Repetir Contraseña', 'attr' => array(
                                'class' => 'input-xlarge',
                            )),
                    ))
                    ->add('enviar_mail','checkbox',array(
                        'label' => 'Enviar al mail la contraseña ingresada?',
                        'required' => false,
                        'mapped' => false,
                        'label_attr' => array('colsm' => 'col-sm-4')
                    ))
            ;
        }
        $builder
                ->add('nombre', 'text', array(
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'Nombre'
                    )
                ))
                ->add('apellido', 'text', array(
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'Apellido'
                    )
                ))
                ->add('tipo_documento', 'choice', array(
                    'choices' => array(
                        'DNI' => 'DNI',
                        'CI' => 'CI',
                        'PA' => 'PASAPORTE',
                        'LE' => 'LIBRETA DE ENROLAMIENTO',
                        'LC' => 'LIBRETA CIVICA',
                    ),
                    'attr' => array(
                        'class' => 'input-xlarge',
                    )
                ))
                ->add('numero_documento', 'text', array(
                    'required'=>false,
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'Número de Documento'
                    )
                ))
                ->add('email', 'email', array(
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'E-mail'
                    )
                ))
                ->add('telefono', 'text', array(
                    'required' => false,
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'Télefono'
                    )
                ))
                ->add('telefonoAlternativo', 'text', array(
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'Télefono Alternativo'
                    ),
                    'required' => false
                ));
                $perfil_id = $this->usuario->getPerfil()->getId();
                $builder->add('perfil', 'entity', array(
                            'attr' => array(
                                'class' => 'input-xlarge',
                            ),
                            'class' => 'UsuarioBundle:Perfil',
                            'query_builder' => function(EntityRepository $er) {
                                return $er->createQueryBuilder('u')
                                    ->orderBy('u.nombre', 'ASC')
                                    ;
                            },
                        ));
        
    }

    public function getName() {
        return 'pac_usuariobundle_usuariotype';
    }

}
