<?php

declare(strict_types=1);

namespace App\Command;

use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Command\Command;
use Cake\I18n\FrozenDate;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\Locator\LocatorAwareTrait;

class BadgeDistributorCommand extends Command
{
    use LocatorAwareTrait;

    protected $badgesTable;
    protected $badgesUsersTable;
    protected $sadhanasTable;
    protected $io;
    protected $today;
    protected $badges;

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('type', [
            'short' => 't',
            'help' => 'Type of badges to distribute',
            'required' => true,
            'choices' => ['day', 'week'],
        ]);

        return $parser;
    }


    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->badgesTable = $this->fetchTable('Badges');
        $this->badgesUsersTable = $this->fetchTable('BadgesUsers');
        $this->sadhanasTable = $this->fetchTable('Sadhanas');

        $this->io = $io;

        $this->io->setStyle('warn', ['background' => 'red']);
        $this->io->setStyle('info', ['background' => 'blue']);
        $this->io->setStyle('ok', ['background' => 'green', 'text' => 'black']);

        FrozenDate::setDefaultLocale('hu-HU');
        $this->today = new FrozenDate();

        $this->badges = $this->badgesTable->find();

        if ($args->getOption('type') == 'week') {
            $this->mondayMorning();
        }
        if ($args->getOption('type') == 'day') {
            $this->everyMorning();
        }
    }

    public function mondayMorning()
    {
        $dateStart = $this->today->startOfWeek();
        $dateEnd = $this->today->endOfWeek();

        $this->io->out('Distributing badges for the week ' . $dateStart . ' to ' . $dateEnd);

        foreach ($this->badges as $badge) {
            if ($badge->field == 'all' && $badge->base = 'count') {
                // TODO
            }
            if ($badge->field == 'all' && $badge->base = 'point') {
                $sadhanas = $this->sadhanasTable->find()
                    ->where(['Sadhanas.date >=' => $dateStart, 'Sadhanas.date <=' => $dateEnd]);
                $gainedBy = $sadhanas->find('stats');
                $data = [
                    'badge_id' => $badge->id,
                    'user_id' => $gainedBy->first()->user_id,
                ];
                $badgeUser = $this->badgesUsersTable->newEntity($data);
                if ($this->badgesUsersTable->save($badgeUser)) {
                    $this->io->out('<ok>Badge ' . $badge->name . ' given to ' . $gainedBy->first()->user . '.</ok>');
                } else {
                    $this->io->out('<error>Badge ' . $badge->name . ' could not be given to user.</error>');
                }
            }
        }
    }

    public function everyMorning()
    {
        $this->io->out('Distributing badges for ' . $this->today->subDay());

        // these badges can be gained only ones
        foreach ($this->badges as $badge) {

            $sadhanas = $this->sadhanasTable->find()
                ->where(['Sadhanas.date <=' => $this->today->subDay()]);

            $usersAlreadyGained = $this->badgesUsersTable->find()
                ->where(['badge_id' => $badge->id])
                ->select(['user_id']);

            if ($badge->field != 'all' && $badge->base == 'count') {
                $gainedBy = $sadhanas->find('all')
                    ->select(['user_id', 'count' => $sadhanas->func()->sum($badge->field)])
                    ->where(['user_id NOT IN' => $usersAlreadyGained])
                    ->group('user_id')
                    ->having(['count >=' => $badge->goal]);
                $this->saveBadge($gainedBy, $badge);
            }
            if ($badge->field != 'all' && $badge->base == 'point') {
                $gainedBy = $sadhanas->find('points', ['elements' => $badge->field])
                    ->where(['user_id NOT IN' => $usersAlreadyGained])
                    ->group('user_id')
                    ->having(['points >=' => $badge->goal]);
                $this->saveBadge($gainedBy, $badge);
            }
        }
    }

    private function saveBadge(Query $gainedBy, Entity $badge)
    {
        $data = [];
        foreach ($gainedBy as $user) {
            $data[] = [
                'badge_id' => $badge->id,
                'user_id' => $user->user_id,
            ];
        }
        $badgeUsers = $this->badgesUsersTable->newEntities($data);
        if ($this->badgesUsersTable->saveMany($badgeUsers)) {
            $this->io->out('<ok>Badge ' . $badge->name . ' given to ' . $gainedBy->count() . ' users.</ok>');
        } else {
            $this->io->out('<error>Badge ' . $badge->name . ' could not be given to any users.</error>');
        }
    }
}
