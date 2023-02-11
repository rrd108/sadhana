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
        $this->Authentication->allowUnauthenticated(['login', 'forgotpass']);
    }

    public function login()
    {
        $result = $this->Authentication->getResult();
        if (!$result->isValid()) {
            throw new JsonApiException(null, 'Invalid login');
            return;
        }

        $userIdentity = $this->Authentication->getIdentity();

        $user = $userIdentity->getOriginalData();
        list($user->token, $user->token_expiration) = $this->generateToken();
        $user->last_login = Chronos::now();
        $user->forgotpass = null;
        $user = $this->Users->save($user);

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
        $users = $this->Users->find();
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
        $user = $this->Users->get($id);
        if ($this->request->is(['patch'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!$this->Users->save($user)) {
                $user->errors = $user->getErrors();
            }
        }
        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', ['user']);
    }

    public function forgotpass()
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
                ->deliver('Gauranga @' . strtok($user->email, '@') . '!<br><br>Az új jelszavad: ' . $user->forgotPass . '<br><br>Üdvözlettel,<br>Sadhana');

            $result = [
                'message' => 'Your temporary password is sent to your email.',
                'success' => true
            ];
            $this->set(compact('result'));
            $this->viewBuilder()->setOption('serialize', ['result']);
        }
    }
}
