<?php
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address1')
            ->add('address2')
            ->add('city')
            ->add('zipcode')
            ->add('country')
            ->add('lastname')
            ->add('civility', EntityType::class, array(
                'class' => 'App:Civility',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.rank', 'ASC');
                },
                'choice_label' => 'longLabel',
                'expanded' => false,
                'multiple' => false
            ))
            ->add('mail')
            ->add('phone')
            ->add('firstname');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\Partner']);
    }
}

?>
