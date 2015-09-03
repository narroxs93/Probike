<?php

namespace Cinetic\ReservaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dia','date',array(
                'widget' => 'single_text'
            ))
            ->add('horaInicio', 'text')
            ->add('horaFinal', 'text')
            ->add('nombre','text')
            ->add('apellidos','text')
            ->add('telefono','number')
            ->add('email','email')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cinetic\ReservaBundle\Entity\Reserva'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cinetic_reservabundle_reserva';
    }
}
