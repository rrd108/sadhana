<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * CounsellorsCounsulees Controller
 *
 * @property \App\Model\Table\CounsellorsCounsuleesTable $CounsellorsCounsulees
 * @method \App\Model\Entity\CounsellorsCounsulee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CounsellorsCounsuleesController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $counsellorsCounsulee = $this->CounsellorsCounsulees->newEmptyEntity();
        if ($this->request->is('post')) {
            $counsellorsCounsulee = $this->CounsellorsCounsulees->patchEntity($counsellorsCounsulee, $this->request->getData());
            $counsellorsCounsulee->counsulee_id = $this->Authentication->getIdentity()->id;
            if (!$this->CounsellorsCounsulees->save($counsellorsCounsulee)) {
                $counsellorsCounsulee->errors = $counsellorsCounsulee->getErrors();
            }
        }
        $this->set(compact('counsellorsCounsulee'));
        $this->viewBuilder()->setOption('serialize', ['counsellorsCounsulee']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Counsellors Counsulee id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($counsellor_id = null)
    {
        $this->request->allowMethod(['delete']);
        $counsellorsCounsulee = $this->CounsellorsCounsulees->find()
            ->where(['counsellor_id' => $counsellor_id, 'counsulee_id' => $this->Authentication->getIdentity()->id])
            ->first();

        if (!$this->CounsellorsCounsulees->delete($counsellorsCounsulee)) {
            $counsellorsCounsulee->errors = $counsellorsCounsulee->getErrors();
        }
        $this->set(compact('counsellorsCounsulee'));
        $this->viewBuilder()->setOption('serialize', ['counsellorsCounsulee']);
    }
}
