<?php

namespace Softspring\NotificationBundle\Form;

use Softspring\NotificationBundle\Entity\Embeddable\UserNotificationPreference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationPreferenceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserNotificationPreference::class,
        ]);

        $resolver->setDefault('screen_allowed', true);
        $resolver->setDefault('email_allowed', true);
        $resolver->setDefault('push_allowed', true);
        $resolver->setAllowedTypes('screen_allowed', ['bool']);
        $resolver->setAllowedTypes('email_allowed', ['bool']);
        $resolver->setAllowedTypes('push_allowed', ['bool']);

        $resolver->setDefault('screen_options', []);
        $resolver->setDefault('email_options', []);
        $resolver->setDefault('push_options', []);
        $resolver->setAllowedTypes('screen_options', ['array']);
        $resolver->setAllowedTypes('email_options', ['array']);
        $resolver->setAllowedTypes('push_options', ['array']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['screen_allowed']) {
            $builder->add('screen', CheckboxType::class, array_merge([
                'required' => false,
            ], $options['screen_options']));
        }
        if ($options['email_allowed']) {
            $builder->add('email', CheckboxType::class, array_merge([
                'required' => false,
            ], $options['email_options']));
        }
        if ($options['push_allowed']) {
            $builder->add('push', CheckboxType::class, array_merge([
                'required' => false,
            ], $options['push_options']));
        }
    }
}