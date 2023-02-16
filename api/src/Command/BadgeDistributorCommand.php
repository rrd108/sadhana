<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Core\Configure;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\ORM\Locator\LocatorAwareTrait;

class BadgeDistributorCommand extends Command
{
    use LocatorAwareTrait;

    public function execute(Arguments $args, ConsoleIo $io)
    {
        Configure::load('sadhana', 'default', false);
        $sadhanaCalculation = Configure::read('sadhana');

        $dateStart = '2023-02-06';  // TODO
        $dateEnd = '2023-02-16';    // TODO

        $badgesTable = $this->fetchTable('Badges');
        $badgesUsersTable = $this->fetchTable('BadgesUsers');
        $sadhanasTable = $this->fetchTable('Sadhanas');

        $io->setStyle('warn', ['background' => 'red']);
        $io->setStyle('info', ['background' => 'blue']);
        $io->setStyle('ok', ['background' => 'green', 'text' => 'black']);

        $io->out('Distributing badges for the period ' . $dateStart . ' to ' . $dateEnd);
        $badges = $badgesTable->find();

        $sadhanas = $sadhanasTable->find()
            ->where(['date >=' => $dateStart, 'date <=' => $dateEnd]);

        foreach ($badges as $badge) {
            if ($badge->field == 'all' && $badge->base = 'point') {
                $gainedBy = $sadhanas->find('stats');
                $data = [
                    'badge_id' => $badge->id,
                    'user_id' => $gainedBy->first()->userId,
                ];
                $badgeUser = $badgesUsersTable->newEntity($data);
                if ($badgesUsersTable->save($badgeUser)) {
                    $io->out('<ok>Badge ' . $badge->name . ' given to user.</ok>');
                } else {
                    $io->out('<error>Badge ' . $badge->name . ' could not be given to user.</error>');
                }
            }

            if ($badge->field != 'all') {
                $fields = explode('+', $badge->field);
            }
        }
    }
}
