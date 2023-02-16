<?php

declare(strict_types=1);

namespace App\Controller;

use JsonApiException\Error\Exception\JsonApiException;

/**
 * BadgesUsers Controller
 *
 * @property \App\Model\Table\BadgesUsersTable $BadgesUsers
 * @method \App\Model\Entity\BadgesUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BadgesUsersController extends AppController
{
    /**
     * Edit method
     *
     * @param string|null $id Badges User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $badgesUser = $this->BadgesUsers->get($id);
        if ($this->request->is(['patch'])) {
            $badgesUser = $this->BadgesUsers->patchEntity($badgesUser, $this->request->getData());
            if (!$this->BadgesUsers->save($badgesUser)) {
                throw new JsonApiException($badgesUser, 'Gained badge update failed');
            }
        }
        $this->set(compact('badgesUser'));
        $this->viewBuilder()->setOption('serialize', 'badgesUser');
    }
}
