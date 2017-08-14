<?php
namespace TournamentBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TournamentBundle\Document\Tournament;

abstract class TournamentManagerController extends Controller
{
    protected function getTournament($id):Tournament
    {
        $repo = $this->getTMRepository('Tournament');
        /** @var Tournament $tournament */
        $tournament = $repo->find($id);

        if (is_null($tournament)) {
            throw $this->createNotFoundException('Tournament not found');
        }

        return $tournament;
    }

    protected function getDocumentManager():DocumentManager
    {
        return $this->get('doctrine_mongodb')->getManager();
    }

    protected function getTMRepository($document):DocumentRepository
    {
        return $this->get('doctrine_mongodb')->getRepository('TournamentBundle:'.$document);
    }
}
