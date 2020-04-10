<?php

namespace AlbumReview\AlbumReviewBundle\Form;

use GuzzleHttp\Client as Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Translation\Tests\StringClass;


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
          //  ->add('track_list', null, ['required'=> false])
            ->add('review')
            ->add('image', Filetype::class, array('label' => 'upload an image', 'data_class' => null))
            ->add('submit', SubmitType::class);

       /* $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getArtist(), $data->getTitle());
            }
        );*/

        /*$builder->get('artist')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $artist = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $artist);
            }
        );

        $builder->get('title')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $title = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $title);
            }
        );*/


        /*$builder->get('track_list')
            ->addModelTransformer(new CallbackTransformer(
                function ($tracksAsArray) {
                    // transform the array to a string
                    return implode(',', $tracksAsArray);
                },
                function ($tracksAsString) {
                    // transform the string back to an array
                    return explode(',', $tracksAsString);
                }
            ));*/
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
