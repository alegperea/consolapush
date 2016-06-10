<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MiPerfilPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldpassword','password',array(
                'required' => false,
                'label' => "Contraseña Actual",
                'always_empty' => true,
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las dos contraseñas deben coincidir',
                'first_name' => 'Contraseña',
                'second_name' => 'Repetir Contraseña',
            ))
        ;
    }

    public function getName()
    {
        return 'pac_usuariobundle_myperfilpasstype';
    }
}
