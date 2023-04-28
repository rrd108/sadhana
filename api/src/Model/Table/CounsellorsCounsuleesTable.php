<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CounsellorsCounsulees Model
 *
 * @method \App\Model\Entity\CounsellorsCounsulee newEmptyEntity()
 * @method \App\Model\Entity\CounsellorsCounsulee newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee get($primaryKey, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CounsellorsCounsulee[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CounsellorsCounsuleesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('counsellors_counsulees');

        $this->belongsTo('Users', [
            'foreignKey' => 'counsellor_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'counsulee_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('counsellor_id')
            ->requirePresence('counsellor_id', 'create')
            ->notEmptyString('counsellor_id');

        $validator
            ->uuid('counsulee_id')
            ->requirePresence('counsulee_id', 'create')
            ->notEmptyString('counsulee_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('counsellor_id', 'Users'), ['errorField' => 'counsellor_id']);
        $rules->add($rules->existsIn('counsulee_id', 'Users'), ['errorField' => 'counsulee_id']);

        return $rules;
    }
}
