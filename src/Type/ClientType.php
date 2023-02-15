<?php

namespace App\Type;

use App\DTO\ClientEditPayload;
use App\Enum\EducationEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber', TextType::class, [
                'help' => '+70000000000',
            ])
            ->add('email')
            ->add('education', EnumType::class, [
                'class' => EducationEnum::class,
                'choice_label' => fn (EducationEnum $case) => $case->value,
            ])
            ->add('consentProcessingPersonalData', CheckboxType::class, [
                'label' => 'I consent to the processing of my personal data',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientEditPayload::class,
        ]);
    }
}
