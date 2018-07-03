<?php
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class NotificationContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('content')
            ->add('type', EntityType::class, array(
                'class' => 'App:NotificationType',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('nt')
                        ->orderBy('nt.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\NotificationContent']);
    }
}

?>
