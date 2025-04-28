<?php
namespace App\Form\Type;

use App\Entity\Brand;
use App\Entity\Category;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DecimalType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('year_of_creation', DateType::class)
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    '1	✨' => 1.0,
                    '2	✨' => 2.0,
                    '3	✨' => 3.0,
                    '4	✨' => 4.0,
                    '5	✨' => 5.0,
                ],
                'placeholder' => 'Select rating',])

            ->add('save', SubmitType::class)
        ;
    }
}

