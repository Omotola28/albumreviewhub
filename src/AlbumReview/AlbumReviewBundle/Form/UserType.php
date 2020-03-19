<?php

namespace AlbumReview\AlbumReviewBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles_options', ChoiceType::class, [
            "mapped" => false,
            "attr" => array(
                'class' => "form-control"
            ),
            'choices'  => [
                'Choose...' => null,
                'ROLE_ADMIN' => "ROLE_ADMIN",
                'ROLE_MODERATOR' => "ROLE_MODERATOR",
            ],
        ])
         ->add('GRANT',SubmitType::class)
         ->add('REVOKE', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlbumReview\AlbumReviewBundle\Entity\User',

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'albumreview_albumreviewbundle_user';
    }


}
