<?php

namespace Cinetic\ExcepcionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExcepcionType extends AbstractType
{
    /**
     * TODO:saber com agafar festivo perque no t'agafi cap horari simplemnt que sigui festiu
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text')
            ->add('dia','date')
            ->add('horarios', 'entity', array(
                'multiple' => true,   // Multiple selection allowed
                'expanded' => true,   // Render as checkboxes
                //'choice_label' => '', // Assuming that the entity has a "name" property
                'class'    => 'Cinetic\HorarioBundle\Entity\Horario',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cinetic\ExcepcionBundle\Entity\Excepcion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cinetic_excepcionbundle_excepcion';
    }
}
