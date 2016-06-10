<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            //->add('fecha_baja')
            //->add('usuario_baja')
            ->add('activo','checkbox')
            //->add('perfiles')
        ;
    }

    public function getName()
    {
        return 'pac_usuariobundle_roltype';
    }
}
