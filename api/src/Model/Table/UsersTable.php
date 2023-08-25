<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\BadgesTable&\Cake\ORM\Association\BelongsToMany $Badges
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Badges', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'badge_id',
            'joinTable' => 'badges_users',
            'sort' => ['BadgesUsers.created' => 'DESC'],
        ]);

        $this->belongsToMany('Counsulees', [
            'className' => 'Users',
            'foreignKey' => 'counsellor_id',
            'targetForeignKey' => 'counsulee_id',
            'joinTable' => 'counsellors_counsulees',
            'finder' => 'onlyIds'
        ]);

        $this->belongsToMany('Counsellors', [
            'className' => 'Users',
            'foreignKey' => 'counsulee_id',
            'targetForeignKey' => 'counsellor_id',
            'joinTable' => 'counsellors_counsulees',
            'finder' => 'onlyIds'
        ]);

        $this->hasMany('CounsellorsCounsulees', [
            'foreignKey' => 'counsulee_id',
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

        $validator
            ->dateTime('token_expiration')
            ->allowEmptyDateTime('token_expiration');

        $validator
            ->dateTime('last_login')
            ->allowEmptyDateTime('last_login');

        $validator
            ->scalar('role')
            ->maxLength('role', 10)
            ->notEmptyString('role');

        $validator
            ->scalar('forgotPass')
            ->maxLength('forgotPass', 12)
            ->allowEmptyString('forgotPass');

        $validator
            ->scalar('firebaseUserToken')
            ->maxLength('firebaseUserToken', 255)
            ->allowEmptyString('firebaseUserToken');

        $validator
            ->scalar('notificationTime')
            ->maxLength('notificationTime', 2)
            ->allowEmptyString('notificationTime');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    public function findOnlyIds(Query $query, array $options)
    {
        return $query->select(['id']);
    }

    public function findTopBadges(Query $query, array $options)
    {
        $user = $query->contain(['Badges' => function ($q) {
            return $q->select(['name', 'maxLevel' => $q->func()->max('level')])->group(['name']);
        }]);
        return $user;
    }
}
