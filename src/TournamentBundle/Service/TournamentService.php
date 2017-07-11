<?php
namespace TournamentBundle\Service;


use TournamentBundle\Document\Tournament;

class TournamentService
{
    /**
     * - sets the status of the tournament as "started",
     * - sets round to one
     * - randomises pairing for the first round
     *
     * @param Tournament $tournament
     * @return Tournament
     */
    public function startTournament(Tournament $tournament):Tournament
    {

    }
}