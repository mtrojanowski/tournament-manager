<?php
namespace TournamentBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use TournamentBundle\Document\Team;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Team $team */
        $team = $options['data'];

        $builder
            ->add('name', TextType::class)
            ->add('country', TextType::class, ['data' => 'Poland'])
            ->add('club', TextType::class)
            ->add('tournamentId', HiddenType::class, ['data' => $team->getTournamentId()])
            ->add('save', SubmitType::class, ['label' => 'Add team'])
            ->getForm();
    }
}
