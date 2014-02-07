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
            ->add('slug', null, array('label' => 'node.form.slug' ,'label_attr' => array(
                'class' => 'control-label'
            )))
            ->add('title', null, array('label' => 'node.form.title'))
            ->add('visible', null, array('label' => 'node.form.visible'))
            ->add('metaTitle',null, array('label' => 'node.form.metaTitle'))
            ->add('metaDescription', null, array('label' => 'node.form.metaDescription'))
            ->add('metaKeywords', null, array('label' => 'node.form.metaKeywords'))
            ->add('ogTitle', null, array('label' => 'node.form.ogTitle'))
            ->add('ogDescription', null, array('label' => 'node.form.ogDescription'))
            ->add('ogImage', null, array(
                'label' => 'node.form.ogImage',
                'attr'  => array('class' => 'btn-media'),
                'empty_value' => 'node.form.ogImage_empty_value'
                ))
            ->add('link', null, array('label' => 'node.form.link'))
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
