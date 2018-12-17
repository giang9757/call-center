<?php
namespace App\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;

/**
 * Memocaller Controller
 *
 * @property \App\Model\Table\MemocallerTable $Memocaller
 *
 * @method \App\Model\Entity\Memocaller[] paginate($object = null, array $settings = [])
 */
class MemocallerController extends AppController
{
    /**
     * beforeFilter method
     * @author nobi/ giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */

    public function beforeFilter(Event $event)
    {  
        if($this->request->session()->read('uid')){ 
            $Users = TableRegistry::get('User');
            $user = $Users->get($this->request->session()->read('uid'));          
            $this->set('user', $user);
        }else{
            $this->redirect('/User');
        }

    } 
    /**
     * showCallLogByPeople method
     *
     * @return \Cake\Http\Response|void
     */
    public function showCallLogByPeople($num)
    {
        $Users = TableRegistry::get('User');
        $user = $Users->get($this->request->session()->read('uid')); 
        
        if (preg_match("/^[\d\+\-\(\) ]+$/", $num)) {
            $newNum='+81'.ltrim($num, '0');
           
               
        }else{
            $num=$user->callerId;
            $newNum=$num;
        }
        $Guests = TableRegistry::get('guest_list');
        if($Guests->exists(['num' => $num])){                
            $info=$Guests->get($num); 
        }else{
            $info= (object) array(
                'name'=>'Center',
                'num'=>str_replace('+81', '0', $num),
                'orderId'=> ''
            ) ;             
        }            
        $callData = $this->Memocaller->find('all')->where([
            'accountSid'=>$user->accountSid,
            'OR' => [
                'callFrom' => $newNum,
                'callTo' => $newNum,   
            ]

        ])->order(['date' => 'DESC'])->limit(150)->execute()->fetchAll('assoc');
        $this->set(compact('info','callData'));
    }
     /**
     * changeInfor method
     *
     * @return \Cake\Http\Response|void
     */
    public function saveInfo()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {  

            $guests = TableRegistry::get('guest_list');       
            $newRecord = $guests->newEntity();           
            $newRecord->num=$this->request->data['phone'];
            $newRecord->name=$this->request->data['name'];
            $newRecord->orderId=$this->request->data['order'];
            $guests->save($newRecord); 
            $this->Flash->set(__('Data has been saved'));
            $this->redirect(['action' => 'showCallLogByPeople/'.$newRecord->num]);         
        }     
    }
}
