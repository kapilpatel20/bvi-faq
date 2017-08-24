<?php

namespace BviFaqBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FaqForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder,array $options) {

    	$builder->add('question', 'text')
                ->add('answer', 'textarea')
                ->add('status', 'choice', array('choices' => array('Active' => 'Active', 'Inactive' => 'Inactive'), 'multiple' => false, 'expanded' => true,
                'empty_value' => false));
    }

    public function getName() {
        return 'faq';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BviFaqBundle\Entity\Faq'
        ));
    }

}

