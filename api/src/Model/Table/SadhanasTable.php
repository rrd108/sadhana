<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sadhanas Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Sadhana newEmptyEntity()
 * @method \App\Model\Entity\Sadhana newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Sadhana[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sadhana get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sadhana findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Sadhana patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sadhana[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sadhana|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sadhana saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sadhana[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sadhana[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sadhana[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sadhana[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SadhanasTable extends Table
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

        $this->setTable('sadhanas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->uuid('user_id')
            ->notEmptyString('user_id');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->notEmptyString('japaEarly');

        $validator
            ->notEmptyString('japaMorning');

        $validator
            ->notEmptyString('japaAfternoon');

        $validator
            ->notEmptyString('japaNight');

        $validator
            ->boolean('mangala')
            ->notEmptyString('mangala');

        $validator
            ->boolean('japa')
            ->notEmptyString('japa');

        $validator
            ->boolean('kirtana')
            ->notEmptyString('kirtana');

        $validator
            ->boolean('class')
            ->notEmptyString('class');

        $validator
            ->boolean('gauraarati')
            ->notEmptyString('gauraarati');

        $validator
            ->notEmptyString('reading');

        $validator
            ->notEmptyString('study');

        $validator
            ->notEmptyString('murtiseva');

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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->isUnique(['user_id', 'date'], 'Sadhana entry is already in the database.'));
        return $rules;
    }
}
