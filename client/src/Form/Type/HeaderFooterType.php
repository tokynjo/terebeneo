<?php
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class HeaderFooterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header')
            ->add('partner', EntityType::class, array(
                'class' => 'App:Partner',
                'query_builder' => function (EntityRepository $er) {

                    $partners =  $er->createQueryBuilder('p')
                        ->where('p.deleted <> 1')
                        ->orderBy('p.name', 'ASC');
                    /*$partnersWithoutHF = [];
                    foreach($partners as $partner) {
                         if($partner->getActiveHeadersFooters()) {
                             //array_push($partnersWithoutHF, $partner);
                             $partners->remove($partners);
                         }
                    }*/
                    return $partners;
                },
                'choice_label' => 'name',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\HeaderFooter']);
    }
}

?>
