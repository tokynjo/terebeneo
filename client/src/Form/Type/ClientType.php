<?php
namespace App\Form\Type;

use App\Entity\Constants\Constant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', EntityType::class,
                [
                    'class' => 'App:Partner',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->where('p.deleted = 0')
                            ->andWhere('p.parent IS NULL' )
                            ->orderBy('p.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'expanded' => false,
                    'multiple' => false
                ])
            ->add('name')
            ->add('address1')
            ->add('address2')
            ->add('zipCode')
            ->add('city')
            ->add('country')
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
            ->add('lastname')
            ->add('firstname')
            ->add('phone')
            ->add('mobile')
            ->add('mail')
            ->add('category')
            ->add('nbLicense', ChoiceType::class, ['choices' => Constant::$neobeNbLicense])
            ->add('volumeSize', ChoiceType::class, ['choices' => Constant::$neobeVolumeSize]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\Partner']);
    }
}

?>
