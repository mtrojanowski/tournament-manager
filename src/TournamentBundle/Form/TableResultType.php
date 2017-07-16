<?php
namespace TournamentBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use TournamentBundle\Document\TeamResult;

class TableResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team1MatchPoints', TextType::class)
            ->add('team1Penalty', TextType::class)
            ->add('team2MatchPoints', TextType::class)
            ->add('team2Penalty', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
            ->setAction($options['action'])
            ->getForm();
    }

}