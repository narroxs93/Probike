<?php

namespace Cinetic\DiaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('horarios', 'entity', array(
                'multiple' => true,   // Multiple selection allowed
                'expanded' => true,   // Render as checkboxes
                //'choice_label' => '', // Assuming that the entity has a "name" property
                'class'    => 'Cinetic\HorarioBundle\Entity\Horario',
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cinetic\DiaBundle\Entity\Dia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cinetic_diabundle_dia';
    }
}
