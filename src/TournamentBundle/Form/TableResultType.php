<?php
namespace TournamentBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TableResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team1MatchPoints', TextType::class)
            ->add('team1Scenario', CheckboxType::class, ['required' => false])
            ->add('team1Penalty', TextType::class, ['required' => false])
            ->add('team2MatchPoints', TextType::class, ['required' => isset($options['data']['isBye']) && !$options['data']['isBye']])
            ->add('team2Scenario', CheckboxType::class, ['required' => false])
            ->add('team2Penalty', TextType::class, ['required' => false])
            ->add('tableNo', HiddenType::class)
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
            ->getForm();
    }

}