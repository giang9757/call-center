<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * User Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UserTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user');
        $this->setDisplayField('name');
        $this->setPrimaryKey('email');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->email('email')
            ->allowEmpty('email', 'create');

        $validator
            ->scalar('company')
            ->requirePresence('company', 'create')
            ->notEmpty('company');

        $validator
            ->integer('access')
            ->requirePresence('access', 'create')
            ->notEmpty('access');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('pass')
            ->requirePresence('pass', 'create')
            ->notEmpty('pass');
         $validator
            ->scalar('image')
            ->requirePresence('image', 'create')
            ->notEmpty('image');

        $validator
            ->scalar('accountSid')
            ->allowEmpty('accountSid');

        $validator
            ->scalar('authToken')
            ->allowEmpty('authToken');

        $validator
            ->scalar('appSid')
            ->allowEmpty('appSid');

        $validator
            ->scalar('callerId')
            ->allowEmpty('callerId');

        $validator
            ->scalar('confirm')
            ->allowEmpty('confirm');

        $validator
            ->scalar('active')
            ->allowEmpty('active');
        
        $validator
            ->scalar('timeConfirm')
            ->allowEmpty('timeConfirm');

        $validator
            ->scalar('timeActive')
            ->allowEmpty('timeActive');  

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
