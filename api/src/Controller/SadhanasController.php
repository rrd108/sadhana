<?php

declare(strict_types=1);

namespace App\Controller;

use JsonApiException\Error\Exception\JsonApiException;

/**
 * Sadhanas Controller
 *
 * @property \App\Model\Table\SadhanasTable $Sadhanas
 * @method \App\Model\Entity\Sadhana[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SadhanasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $sadhanas = $this->paginate($this->Sadhanas);

        $this->set(compact('sadhanas'));
    }

    /**
     * View method
     *
     * @param string|null $id Sadhana id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sadhana = $this->Sadhanas->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('sadhana'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sadhana = $this->Sadhanas->newEmptyEntity();
        if ($this->request->is('post')) {
            $sadhana = $this->Sadhanas->patchEntity($sadhana, $this->request->getData());
            $sadhana->user_id = $this->Authentication->getIdentity()->id;
            if (!$this->Sadhanas->save($sadhana)) {
                throw new JsonApiException($sadhana, 'Sadhana entry did not saved');
            }
        }
        unset($sadhana->user_id);
        $this->set(compact('sadhana'));
        $this->viewBuilder()->setOption('serialize', 'sadhana');
    }

    /**
     * Edit method
     *
     * @param string|null $id Sadhana id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sadhana = $this->Sadhanas->get($id);
        if ($this->request->is(['patch'])) {
            $sadhana = $this->Sadhanas->patchEntity($sadhana, $this->request->getData());
            $sadhana->user_id = $this->Authentication->getIdentity()->id;
            if ($this->Sadhanas->save($sadhana)) {
                if (!$this->Sadhanas->save($sadhana)) {
                    throw new JsonApiException($sadhana, 'Sadhana entry did not updated');
                }
            }
            unset($sadhana->user_id);
            $this->set(compact('sadhana'));
            $this->viewBuilder()->setOption('serialize', 'sadhana');
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Sadhana id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sadhana = $this->Sadhanas->get($id);
        if ($this->Sadhanas->delete($sadhana)) {
            $this->Flash->success(__('The sadhana has been deleted.'));
        } else {
            $this->Flash->error(__('The sadhana could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
