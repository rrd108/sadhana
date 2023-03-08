<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Mailer\Mailer;
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
    protected $log = false;

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('type', [
            'short' => 't',
            'help' => 'Type of badges to distribute',
            'required' => true,
            'choices' => ['day', 'week'],
        ]);

        $parser->addOption('log', [
            'short' => 'l',
            'help' => 'write log file',
            'boolean' => true,
        ]);

        return $parser;
    }

    private function logger(string $message)
    {
        if ($this->log) {
            Log::debug($message, 'badgeDistribution');
        }
        $this->io->out($message);
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->log = $args->getOption('log');
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

        $mailer = new Mailer('default');
        $logContent = file_get_contents(LOGS . 'badgeDistribution-' . date('ymd') . '.log');
        $mailer->setFrom(['notifications@sadhana.1108.cc' => 'Sadhana Notification'])
            ->setTo('rrd@1108.cc')
            ->setSubject('Sadhana Badge Distribution Log')
            ->deliver($logContent);
    }

    public function mondayMorning()
    {
        $dateStart = $this->today->subDay()->startOfWeek(); // cron runs at monday morning so we have to calculate for previous week
        $dateEnd = $this->today->subDay()->endOfWeek();

        $this->logger('Distributing badges for the week ' . $dateStart . ' to ' . $dateEnd);

        foreach ($this->badges as $badge) {
            if ($badge->level === 0 && $badge->base = 'count') {
                $this->logger('<warn>TODO not implemented see #18</warn>');
            }
            if ($badge->level === 0 && $badge->base = 'point') {
                $sadhanas = $this->sadhanasTable->find()
                    ->where(['Sadhanas.date >=' => $dateStart, 'Sadhanas.date <=' => $dateEnd]);
                $gainedBy = $sadhanas->find('points', ['elements' => $badge->field]);
                $data = [
                    'badge_id' => $badge->id,
                    'user_id' => $gainedBy->first()->user_id,
                ];
                $badgeUser = $this->badgesUsersTable->newEntity($data);
                if ($this->badgesUsersTable->save($badgeUser)) {
                    $this->logger('<ok>Badge ' . $badge->name . ' given to ' . $gainedBy->first()->user . '.</ok>');
                } else {
                    $this->logger('<info>Badge ' . $badge->name . ' could not be given to user.</info>');
                }
            }
        }
    }

    public function everyMorning()
    {
        $this->logger('Distributing badges for ' . $this->today->subDay());

        // these badges can be gained only ones
        foreach ($this->badges as $badge) {

            $sadhanas = $this->sadhanasTable->find()
                ->where(['Sadhanas.date <=' => $this->today->subDay()]);

            $usersAlreadyGained = $this->badgesUsersTable->find()
                ->where(['badge_id' => $badge->id])
                ->select(['user_id']);

            if ($badge->level && $badge->base == 'count') {
                $gainedBy = $sadhanas->find('all')
                    ->select(['user_id', 'count' => $sadhanas->func()->sum($badge->field)])
                    ->where(['user_id NOT IN' => $usersAlreadyGained])
                    ->group('user_id')
                    ->having(['count >=' => $badge->goal]);
                $this->saveBadge($gainedBy, $badge);
            }
            if ($badge->level && $badge->base == 'point') {
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
            $this->logger('<ok>Badge ' . $badge->name . ' given to ' . count($data) . ' users.</ok>');
        } else {
            $this->logger('<info>Badge ' . $badge->name . ' could not be given to any users.</info>');
        }
    }
}
