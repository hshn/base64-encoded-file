<?php


namespace Hshn\Base64EncodedFile\Form\Type;

use Hshn\Base64EncodedFile\Form\DataTransformer\FileToBase64EncodedStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->addViewTransformer(new FileToBase64EncodedStringTransformer($options['strict_decode']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'compound' => false,
                'data_class' => null,
                'empty_data' => null,
                'multiple' => false,
                'strict_decode' => true,
            ])
            ->setAllowedTypes('strict_decode', 'bool')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'file_base64_encoded';
    }
}
