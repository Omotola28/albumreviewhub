<?php

namespace AlbumReview\AlbumReviewBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class AlbumEntryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist')
            ->add('title')
            ->add('track_list', null, ['required'=> true])
            ->add('review')
            ->add('image', Filetype::class, array('label' => 'upload an image', 'data_class' => null))
            ->add('submit', SubmitType::class);

        $builder->get('track_list')
            ->addModelTransformer(new CallbackTransformer(
                function ($tracksAsArray) {
                    // transform the array to a string
                    return implode(',', $tracksAsArray);
                },
                function ($tracksAsString) {
                    // transform the string back to an array
                    return explode(',', $tracksAsString);
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlbumReview\AlbumReviewBundle\Entity\AlbumEntry'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'albumreview_albumreviewbundle_albumentry';
    }


}
