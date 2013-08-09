<?php

namespace Btn\NodesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', null, array('label' => 'Nice URL' ,'label_attr' => array(
                'class' => 'control-label'
            )))
            ->add('title', null, array('label' => 'Menu name'))
            ->add('visible', null, array('label' => 'Show in menu'))
            ->add('metaTitle')
            ->add('metaDescription')
            ->add('metaKeywords')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Btn\NodesBundle\Entity\Node'
        ));
    }

    public function getName()
    {
        return 'btn_nodesbundle_nodetype';
    }
}
