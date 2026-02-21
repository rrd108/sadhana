<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;

class RemindersCommand extends Command
{
    use LocatorAwareTrait;

    protected $usersTable;
    protected $sadhanasTable;
    protected $io;

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->io = $io;
        $this->usersTable = $this->fetchTable('Users');
        $this->sadhanasTable = $this->fetchTable('Sadhanas');

        $io->out('Starting reminder check...');
        $io->out('Debug: Current hour: ' . (int)(new FrozenTime())->format('G'));

        $tokens = $this->getTokensForReminders();

        if (empty($tokens)) {
            $io->out('No tokens found for this hour');

            return;
        }

        $io->out('Found ' . count($tokens) . ' tokens to send notifications');
        $io->out('Debug: Tokens: ' . implode(', ', array_map(fn($t) => substr($t, 0, 20) . '...', $tokens)));

        $this->sendNotifications($tokens);
    }

    private function getTokensForReminders(): array
    {
        $currentHour = (int)(new FrozenTime())->format('G');
        $targetHour = ($currentHour + 1) % 24;
        $today = (new FrozenTime())->format('Y-m-d');

        $this->io->out("Debug: Looking for users with notificationTime = $targetHour");
        $this->io->out("Debug: Excluding users who have sadhana for today ($today)");

        $excludeSubquery = $this->sadhanasTable->find()
            ->select(['user_id'])
            ->where(['date' => $today]);

        $users = $this->usersTable->find()
            ->select(['id', 'firebaseUserToken', 'notificationTime'])
            ->where([
                'firebaseUserToken IS NOT NULL',
                'notificationTime IS NOT NULL',
                'notificationTime' => $targetHour,
            ])
            ->where(function ($q) use ($excludeSubquery) {
                return $q->notIn('Users.id', $excludeSubquery);
            });

        $this->io->out('Debug: SQL: ' . $users->sql());

        $tokens = [];
        $debugUsers = [];
        foreach ($users as $user) {
            if (!empty($user->firebaseUserToken)) {
                $tokens[] = $user->firebaseUserToken;
                $debugUsers[] = "user_id={$user->id}, notificationTime={$user->notificationTime}";
            }
        }

        if (!empty($debugUsers)) {
            $this->io->out('Debug: Matching users: ' . implode('; ', $debugUsers));
        }

        return $tokens;
    }

    private function sendNotifications(array $tokens): void
    {
        $configPath = CONFIG . 'sadhana-firebase.json';

        if (!file_exists($configPath)) {
            $this->io->error('Firebase config file not found: ' . $configPath);

            return;
        }

        $this->io->out('Debug: Firebase config loaded from: ' . $configPath);

        $factory = (new Factory())->withServiceAccount($configPath);
        $messaging = $factory->createMessaging();
        $this->io->out('Debug: Firebase messaging initialized');

        $currentTime = (new FrozenTime())->format('Y-m-d H:i:s');
        $title = '😱 Sadhana emlékető';
        $body = $currentTime . "\nMa még nem töltötted ki a sadhana infókat!";

        $message = CloudMessage::new()
            ->withNotification(['title' => $title, 'body' => $body])
            ->withData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK']);

        $this->io->out('Debug: Sending multicast to ' . count($tokens) . ' tokens...');

        $report = $messaging->sendMulticast($message, $tokens);

        $this->io->out('Successfully sent: ' . $report->successes()->count());
        $this->io->out('Failed: ' . $report->failures()->count());

        $failedTokens = [];
        /** @var MulticastSendReport $report */
        foreach ($report->failures()->getItems() as $failure) {
            $this->io->error('Failed: ' . $failure->error()->getMessage());
            $target = $failure->target()->value();
            $failedTokens[] = $target;
        }

        if (!empty($failedTokens)) {
            $this->io->out('Removing ' . count($failedTokens) . ' invalid tokens...');
            $this->removeInvalidTokens($failedTokens);
        }
    }

    private function removeInvalidTokens(array $tokens): void
    {
        foreach ($tokens as $token) {
            $user = $this->usersTable->find()
                ->where(['firebaseUserToken' => $token])
                ->first();

            if ($user) {
                $user->firebaseUserToken = null;
                $this->usersTable->save($user);
                $this->io->out('Removed token for user: ' . $user->id);
            }
        }
    }
}
