<?php

namespace TournamentBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * TeamsRepository
 *
 * This class was generated by the Doctrine ODM. Add your own custom
 * repository methods below.
 */
class TeamsRepository extends DocumentRepository
{
    public function getStandings(string $tournamentId)
    {
        return $this->getStandingsBuilder($tournamentId)
            ->getQuery()
            ->execute()
            ->toArray(false);
    }

    public function getTeamsPlaying(string $tournamentId, bool $dayTwo)
    {
        $builder = $this->getStandingsBuilder($tournamentId);

        if ($dayTwo) {
            $builder
                ->field('confirmedDay2')->equals(true);
        }

        return $builder
            ->getQuery()
            ->execute()
            ->toArray(false);
    }

    public function getFinalStandings(string $tournamentId)
    {
        return $this->createQueryBuilder()
            ->field('tournamentId')->equals($tournamentId)
            ->field('confirmedDay1')->equals(true)
            ->sort('finalBattlePoints', -1)
            ->sort('battlePoints', -1)
            ->sort('matchPoints', -1)
            ->getQuery()
            ->execute()
            ->toArray(false);

    }

    private function getStandingsBuilder(string $tournamentId):Builder
    {
        return $this->createQueryBuilder()
            ->field('tournamentId')->equals($tournamentId)
            ->field('confirmedDay1')->equals(true)
            ->sort('battlePoints', -1)
            ->sort('matchPoints', -1);
    }

}
