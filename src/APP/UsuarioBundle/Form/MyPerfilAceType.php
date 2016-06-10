<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Anfler\GestionBundle\Form\PasajeroType;
    
class MyPerfilAceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('attr'=>array(
                'placeholder' => 'Nombre',
            )))
            ->add('apellido','text',array('attr'=>array(
                'placeholder' => 'Apellido',
            )))
            ->add('telefono')
            ->add('telefonoAlternativo')
            ->add('foto', 'file', array('required' => false))
            ->add('oldpassword','password',array(
                'label' => 'Contraseña Actual',
                'attr' => array('placeholder' => "Contraseña Actual",
                                'class' => 'input-xlarge'),
                'mapped' => false
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las dos contraseñas deben coincidir',
                'first_options' => array( 'label' => 'Nueva Contraseña',
                                            'attr' => array('placeholder' => 'Ingrese una Contraseña',
                                                            'class' => 'input-xlarge')),
                'second_options' => array( 'label' => 'Repetir Nueva Contraseña',
                                            'attr' => array('placeholder' => 'Ingrese Nuevamente la Contraseña',
                                                            'class' => 'input-xlarge')),
                ))

        ;
    }

    public function getName()
    {
        return 'anfler_usuariobundle_myperfilacetype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('editmyperfil'),
        ));
    }
}
