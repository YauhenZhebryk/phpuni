<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordChangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', PasswordType::class, [
                'label' => 'Insert new password',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Insert new password']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Password length must be at least {{ limit }} characters',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
