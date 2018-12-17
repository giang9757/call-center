<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Memocaller Model
 *
 * @method \App\Model\Entity\Memocaller get($primaryKey, $options = [])
 * @method \App\Model\Entity\Memocaller newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Memocaller[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Memocaller|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Memocaller patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Memocaller[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Memocaller findOrCreate($search, callable $callback = null, $options = [])
 */
class MemocallerTable extends Table
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

        $this->setTable('memocaller');
        $this->setDisplayField('callId');
        $this->setPrimaryKey('callId');
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
            ->scalar('callId')
            ->allowEmpty('callId', 'create');

        $validator
            ->scalar('guest_name')
            ->requirePresence('guest_name', 'create')
            ->notEmpty('guest_name');

        $validator
            ->scalar('memo')
            ->requirePresence('memo', 'create')
            ->notEmpty('memo');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->integer('orderId')
            ->requirePresence('orderId', 'create')
            ->notEmpty('orderId');

        return $validator;
    }
}
