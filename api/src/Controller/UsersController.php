<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Chronos\Chronos;
use Cake\Mailer\Mailer;
use Cake\Utility\Security;
use JsonApiException\Error\Exception\JsonApiException;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'forgotpass', 'passreset', 'register']);
    }

    public function login()
    {
        $result = $this->Authentication->getResult();
        if (!$result->isValid()) {
            // try to login with the forgot password
            throw new JsonApiException(null, 'Az emailcím vagy a jelszó nem megfelelő');
            return;
        }

        $userIdentity = $this->Authentication->getIdentity();

        $user = $userIdentity->getOriginalData();
        list($user->token, $user->token_expiration) = $this->generateToken();
        $user->last_login = Chronos::now();
        $user->forgotPass = null;
        $user = $this->Users->save($user);

        $user = $this->Users->get($user->id, ['contain' => ['Counsellors', 'Counsulees']]);

        $user->badges = $this->Users->Badges->getTopBadges($user->id);

        $user->counsulees = collection($user->counsulees)->map(function ($counsulee) {
            unset($counsulee->_joinData);
            return $counsulee->id;
        });

        $user->counsellors = collection($user->counsellors)->map(function ($counsellor) {
            $counsellor->id = $counsellor->_joinData?->counsellor_id;
            unset($counsellor->_joinData);
            return $counsellor->id;
        });

        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', 'user');

        // delete all expired tokens
        $this->Users->updateAll(
            ['token' => null, 'token_expiration' => null],
            ['token_expiration <' => Chronos::now()]
        );
    }

    private function generateToken($length = 36)
    {
        $random = base64_encode(Security::randomBytes($length));
        $cleaned = preg_replace('/[^A-Za-z0-9]/', '', $random);
        return [$cleaned, strtotime('+6 hours')];
    }

    public function index()
    {
        $users = $this->Users->find()->select(['id', 'email', 'name']);
        $this->set(compact('users'));
        $this->viewBuilder()->setOption('serialize', ['users']);
    }

    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!$this->Users->save($user)) {
                $user->errors = $user->getErrors();
            }
        }
        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', ['user']);
    }

    public function edit($id = null)
    {

        if ($this->Authentication->getIdentity()->id != $id) {
            $this->response = $this->response->withStatus(403);
            $this->set(compact('id'));
            $this->viewBuilder()->setOption('serialize', ['id']);
            return;
        }

        $user = $this->Users->get($id, ['contain' => ['Counsellors']]);
        if ($this->request->is(['patch'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!$this->Users->save($user)) {
                $user->errors = $user->getErrors();
            }
        }
        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', ['user']);
    }

    public function register()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->newEmptyEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!$this->Users->save($user)) {
                $user->errors = $user->getErrors();
            }
            $this->set(compact('user'));
            $this->viewBuilder()->setOption('serialize', ['user']);
        }
    }

    public function forgotpass($userId = null, $tempPass = null)
    {
        if ($this->request->is('post')) {
            $user = $this->Users->find()
                ->where(['email' => $this->request->getData('email')])
                ->first();
            if (!$user) {
                throw new JsonApiException(null, 'Invalid email');
                return;
            }
            $user->forgotPass = substr($this->generateToken()[0], 0, 12);
            $user = $this->Users->save($user);

            // send email to the user
            $mailer = new Mailer('default');
            $mailer->setFrom(['forgotpass@sadhana.krisna.hu' => 'Sadhana'])
                ->setTo($user->email)
                ->setSubject('Sadhana elfelejtett jelszó')
                ->deliver('Gauranga @' . strtok($user->email, '@') . '!'
                    . "\n\n" . 'Új jelszó létrehozásához kattints ide: https://sadhana.1108.cc/pass-reset/' . $user->id . '/' . $user->forgotPass
                    . "\n\n" . 'Üdvözlettel,'
                    . "\n" . 'Sadhana');

            $result = [
                'message' => 'You got an email how to log in.',
                'success' => true
            ];
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', ['result']);
        }
    }

    public function passreset()
    {
        if ($this->request->is('patch')) {
            $user = $this->Users->find()
                ->where(['id' => $this->request->getData('id'), 'forgotPass' => $this->request->getData('tempPass')])
                ->firstOrFail();

            $user->forgotPass = null;
            $user->password = $this->request->getData('pass');
            $user = $this->Users->save($user);

            $success = true;
            $this->set(compact('success'));
            $this->viewBuilder()->setOption('serialize', ['success']);
        }
    }
}
