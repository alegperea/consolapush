<?php

namespace APP\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioPassType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                /*
                  ->add('oldpassword','password',array(
                  'required' => false,
                  'label' => "Contraseña Actual",
                  'always_empty' => true,
                  ))
                 */
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Las dos contraseñas deben coincidir',
                    'first_options' => array('label' => 'Contraseña', 'attr' => array(
                            'class' => 'input-xlarge',
                        )),
                    'second_options' => array('label' => 'Repetir Contraseña', 'attr' => array(
                            'class' => 'input-xlarge',
                        ))
                ))
                ->add('enviar_mail', 'checkbox', array(
                    'label' => 'Enviar al mail la contraseña ingresada?',
                    'required' => false,
                    'mapped' => false
                ))
        ;
    }

    public function getName() {
        return 'pac_usuariobundle_usuariopasstype';
    }

}
