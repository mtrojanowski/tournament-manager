<?php
namespace TournamentBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SwitchTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('targetTableNo', TextType::class)
            ->add('targetTeamNo', TextType::class)
            ->add('sourceTableNo', HiddenType::class)
            ->add('sourceTeamNo', HiddenType::class)
            ->add('submit', SubmitType::class, ['label' => 'Switch'])
            ->setAction('')
            ->getForm();
    }

}