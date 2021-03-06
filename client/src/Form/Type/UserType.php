<?php
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('email')
            ->add('lastname')
            ->add('firstname');
        $builder->add(
            'roles',
            ChoiceType::class,
            [
                'choices' => ['roles.administrateur' => 'ROLE_ADMIN', 'roles.api'=>'ROLE_USER' ],
                'expanded' => false,
                'multiple' => true,
                'translation_domain' => 'label'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\User']);
    }
}

?>
