<?php
namespace TournamentBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class TournamentManagerController extends Controller
{
    protected function getDocumentManager():DocumentManager
    {
        return $this->get('doctrine_mongodb')->getManager();
    }

    protected function getTMRepository($document):DocumentRepository
    {
        return $this->get('doctrine_mongodb')->getRepository('TournamentBundle:'.$document);
    }
}
