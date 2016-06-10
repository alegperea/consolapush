<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array(
                    'attr' => array(
                        'class' => 'input-xlarge',
                        'placeholder' => 'Nombre'
                    )
                ))
            ->add('descripcion','textarea',array(
                'attr' => array( ),
                'required' => false
            ))
            ->add('pagina_inicio_default',null, array(
                'required' => true,
                'attr' => array(
                     'class' => 'input-xlarge',
                )
            ))
            ->add('roles',null, array(
                'attr' => array(
                     'class' => 'input-xlarge',
                )
            ))
        ;
    }

    public function getName()
    {
        return 'pac_usuariobundle_perfiltype';
    }
}
