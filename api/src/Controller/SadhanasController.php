<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
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
     * View method
     *
     * @param string|null $id Sadhana id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(string $date)
    {
        $sadhana = $this->Sadhanas->find()
            ->where(['user_id' => $this->Authentication->getIdentity()->id, 'date' => $date])
            ->first();

        unset($sadhana->user_id);
        $this->set(compact('sadhana'));
        $this->viewBuilder()->setOption('serialize', 'sadhana');
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

    public function mystat(string $weekNumber)
    {
        $startDate = new FrozenDate($weekNumber);
        $endDate = $startDate->addDays(6);

        $sadhanas = $this->Sadhanas->find();
        $sadhanaData = Configure::read('sadhana');
        $sadhanas->select([
            'date',
            'japa'  => $sadhanas->func()->sum('japaEarly * ' . $sadhanaData['japaEarly'] . ' + japaMorning * ' . $sadhanaData['japaMorning'] . ' + japaAfternoon * ' . $sadhanaData['japaAfternoon'] . '+ japaNight * ' . $sadhanaData['japaNight']),
            'templeProgram'  => $sadhanas->func()->sum('mangala * ' . $sadhanaData['mangala'] . ' + japa * ' . $sadhanaData['japa'] . ' + kirtana * ' . $sadhanaData['kirtana'] . ' + class * ' . $sadhanaData['class'] . ' + gauraarati * ' . $sadhanaData['gauraarati']),
            'brahmana'  => $sadhanas->func()->sum('reading * ' . $sadhanaData['reading'] . ' + study * ' . $sadhanaData['study'] . ' + murtiseva * ' . $sadhanaData['murtiseva']),
        ])
            ->where([
                'user_id' => $this->Authentication->getIdentity()->id,
                'date >=' => $startDate,
                'date <=' => $endDate,
            ])->group('date');

        $allDates = $this->getBetweenDates($startDate, $endDate);
        $sadhanasDates = $sadhanas->extract('date')->toArray();
        $missingDates = array_diff($allDates, $sadhanasDates);
        $sadhanas = $sadhanas->toArray();
        foreach ($missingDates as $missingDate) {
            $sadhanas[] = ["date" => $missingDate, "japa" => 0, "templeProgram" => 0, "brahmana" => 0];
        }
        usort($sadhanas, function ($a, $b) {
            return $a['date'] <=> $b['date'];
        });

        $this->set(compact('sadhanas'));
        $this->viewBuilder()->setOption('serialize', 'sadhanas')
            ->setOption('jsonOptions', JSON_NUMERIC_CHECK);
    }

    private function getBetweenDates($startDate, $endDate)
    {
        $dateArray = [];
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate = $currentDate->addDay()) {
            $dateArray[] = new FrozenDate($currentDate);
        }
        return $dateArray;
    }

    public function liststat(string $weekNumber)
    {
        $startDate = new FrozenDate($weekNumber);
        $endDate = $startDate->addDays(6);

        $sadhanas = $this->Sadhanas->find()->contain(['Users']);
        $sadhanaData = Configure::read('sadhana');
        $sadhanas->select([
            'user' => 'Users.email',
            'points'  => $sadhanas->func()->sum('japaEarly * ' . $sadhanaData['japaEarly'] . ' + japaMorning * ' . $sadhanaData['japaMorning'] . ' + japaAfternoon * ' . $sadhanaData['japaAfternoon'] . '+ japaNight * ' . $sadhanaData['japaNight'] . ' + mangala * ' . $sadhanaData['mangala'] . ' + japa * ' . $sadhanaData['japa'] . ' + kirtana * ' . $sadhanaData['kirtana'] . ' + class * ' . $sadhanaData['class'] . ' + gauraarati * ' . $sadhanaData['gauraarati'] . ' + reading * ' . $sadhanaData['reading'] . ' + study * ' . $sadhanaData['study'] . ' + murtiseva * ' . $sadhanaData['murtiseva']),
        ])
            ->where([
                'date >=' => $startDate,
                'date <=' => $endDate,
            ])->group('user_id')
            ->order(['points' => 'DESC']);

        $this->set(compact('sadhanas'));
        $this->viewBuilder()->setOption('serialize', 'sadhanas')
            ->setOption('jsonOptions', JSON_NUMERIC_CHECK);
    }
}