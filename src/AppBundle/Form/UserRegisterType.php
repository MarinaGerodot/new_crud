<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\User;

class UserRegisterType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text')
                ->add('email', 'email')
                ->add('password', 'password');
               // ->add('register', 'submit');
    }

    public function getName()
    {
        return 'user';
    }
	
	public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Umbrella\AppBundle\Entity\User',
        );
    }
	
	
	
}