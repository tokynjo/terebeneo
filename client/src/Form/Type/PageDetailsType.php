<?php
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class PageDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logoUpload', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('subdomain')
            ->add('headerTop')
            ->add('footer')
            ->add('color')
            ->add('resume1')
            ->add('product')
            ->add('productPlus')
            ->add('contactEmail')
            ->add('contactPhone')
            ->add('contactTitle')
            ->add('legalMention')
            ->add('cgv')
            ->add('video', TextareaType::class)
            ->add('imageLeftUpload', FileType::class, [
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\PartnerPageDetails']);
    }
}

?>
