<?php


namespace Hshn\Base64EncodedFile\Form\Type;

use Hshn\Base64EncodedFile\Form\DataTransformer\FileToBase64EncodedStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Base64EncodedFileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new FileToBase64EncodedStringTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'compound' => false,
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'empty_data' => null,
                'multiple' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'file_base64_encoded';
    }
}
