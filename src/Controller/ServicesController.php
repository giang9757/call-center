<?php
namespace App\Controller;

use App\Controller\AppController;
use Services_Twilio_Capability;
use Services_Twilio;
use Cake\ORM\TableRegistry;
/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 *
 * @method \App\Model\Entity\Service[] paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
{

    /**
     * Index method
     * @author nobi / giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        
        if($this->request->session()->read('uid')){
            
            $Users=TableRegistry::get('User');
            $user = $Users->get($this->request->session()->read('uid'));
            $emp = $Users
                    ->find('all',['fields' => ['email','name','image','confirm','active']])
                    ->where(['accountSid' => $user->accountSid, 'access'=>2])
                    ->execute()->fetchAll('assoc');
            $this->set(compact('emp'));    
            if($user->access==1){
                $this->set(compact('user'));
                $query = $this->Services->find(
                            'all', 
                            ['conditions' => array(
                                'accountSid' => $user->accountSid,
                            )]
                        );
                if(!$query->isEmpty()){
                    $timeSrv=$this->Services->get($user->accountSid);
                    $this->set('timeSrv',$timeSrv);
                } 
                $activeTab=1;
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $service = $this->Services->patchEntity($timeSrv, $this->request->getData());
                    $path = WWW_ROOT."audio/";
                    switch ($service->time) {
                        case 'timeoutRec':
                            $service->timeoutRec=md5($service->accountSid.'timeoutRec').'.mp3';
                            if($service->filerec['size']>1024000){
                                $this->Flash->error(__('File record size no more than 1MB! Please check and try again.'));
                            }else{
                                
                                if($service->filerec['tmp_name']!=''){
                                                        
                                    if (file_exists($path.$service->timeoutRec)){
                                        unlink($path.$service->timeoutRec);   
                                    }
                                    move_uploaded_file($service->filerec['tmp_name'],$path.$service->timeoutRec);                                           
                                }           

                                if ($this->Services->save($service)) {
                                    $this->Flash->success(__('The file record has been saved. It will take some time for the proposed measures to take effect.'));
                                }else{
                                    $this->Flash->error(__('The file record could not be saved. Please, try again.'));                               
                                }
                                
                            }
                            break;
                        case 'timeinRec':
                            $service->timeinRec=md5($service->accountSid.'timeinRec').'.mp3';
                            if($service->filerec['size']>1024000){
                                $this->Flash->error(__('File record size no more than 1MB! Please check and try again.'));
                            }else{
                                
                                if($service->filerec['tmp_name']!=''){
                                                 
                                    if (file_exists($path.$service->timeinRec)){
                                        unlink($path.$service->timeinRec);   
                                    }
                                    move_uploaded_file($service->filerec['tmp_name'],$path.$service->timeinRec);                                           
                                }           

                                if ($this->Services->save($service)) {
                                    $this->Flash->success(__('The file record has been saved. It will take some time for the proposed measures to take effect.'));
                                }else{
                                    $this->Flash->error(__('The file record could not be saved. Please, try again.'));                               
                                }
                                $activeTab=2;
                            }
                            break;
                        default:
                            $service->busyRec=md5($service->accountSid.'busyRec').'.mp3';
                            if($service->filerec['size']>1024000){
                                $this->Flash->error(__('File record size no more than 1MB! Please check and try again.'));
                            }else{
                                
                                if($service->filerec['tmp_name']!=''){
                                                      
                                    if (file_exists($path.$service->busyRec)){
                                        unlink($path.$service->busyRec);   
                                    }
                                    move_uploaded_file($service->filerec['tmp_name'],$path.$service->busyRec);                                           
                                }           

                                if ($this->Services->save($service)) {
                                    $this->Flash->success(__('The file record has been saved. It will take some time for the proposed measures to take effect.'));
                                }else{
                                    $this->Flash->error(__('The file record could not be saved. Please, try again.'));                               
                                }
                                $activeTab=3;
                            }
                            break;
                    }             
                }

                $this->set(compact('activeTab')); 

            }else{
                $this->Flash->error(__('You can not access Time Service page.'));
                $this->redirect(['controller'=>'User','action' => 'work']);
            }
        }else{

            $this->redirect(['controller'=>'User','action' => 'index']);
        }
        
    }

    /**
     * updateTimeService method
     * @author nobi / giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */
    public function updateTimeService()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
                $service = $this->Services->newEntity();
                $service->accountSid=$this->request->data['accountSid'];
               
                switch ($this->request->data['dayinweek']) {
                    case 'Monday':
                        $service->monday=$this->request->data['val'];
                        break;
                    case 'Tuesday':
                        $service->tuesday=$this->request->data['val'];
                        break;
                    case 'Wednesday':
                        $service->wednesday=$this->request->data['val'];
                        break;
                    case 'Thursday':
                        $service->thursday=$this->request->data['val'];
                        break;
                    case 'Friday':
                        $service->friday=$this->request->data['val'];
                        break;
                    case 'Saturday':
                        $service->saturday=$this->request->data['val'];
                        break;
                    case 'Sunday':
                        $service->sunday=$this->request->data['val'];
                        break;
                    default:
                        $service->holiday=$this->request->data['val'];
                }
            
            }             
        $this->Services->save($service) ;          
    }
    
    /**
     * returnService method
     * @author nobi / giangnt.qb@gmail.com
     * @return xml file
     */
    public function returnService($accountSid)
    {
        $this->viewBuilder()->setLayout('xml-layout');
        if (isset($_REQUEST['roomId'])) {
            $roomId = $_REQUEST['roomId']; 
            $this->set(compact('roomId'));           
        }else{
            $number   = "Center";      
            $Users=TableRegistry::get('User');
            $user = $Users->find(
                                'all',

                                [
                                'fields'=>['callerId'],
                                    'conditions' => array(
                                        'accountSid' => $accountSid,
                                )]
                            )->first();
            $callerId=$user->callerId;
            $weekday=date("l");
            $service=$this->Services->find(
                                'all',

                                [
                                'fields'=>[$weekday,'timeinRec','timeoutRec','busyRec'],
                                'conditions' => array(
                                    'accountSid' => $accountSid,
                                )])->first()->toArray();
            if($service[$weekday]==''){
                $fr='10:00:00';
                $to='15:00:00';
            }else{
                $timesrv=explode('-', $service[$weekday]);
                $fr=$timesrv[0].':00';
                $to=$timesrv[1].':00';
            }
            
            $call_flag=0;
            $now=date('H:i:s');

            if (isset($_REQUEST['PhoneNumber'])) {
                $number = htmlspecialchars($_REQUEST['PhoneNumber']);            
            }
            if (isset($_REQUEST['flag'])) {
                $call_flag = $_REQUEST['flag'];
            }
            if (preg_match("/^[\d\+\-\(\) ]+$/", $number)) {
                $number='+81'.ltrim($number, '0');
                $numberOrClient = "<Number>" . $number . "</Number>";
                $numFlag=1;
            } else {
                $numberOrClient = "<Client>" . $number . "</Client>";
                $numFlag=0;
            }

            $this->set(compact('now','to','fr','call_flag','numberOrClient','numFlag','callerId','service'));
        }
        
    }

     /**
     * second forward method
     * @author nobi / giangnt.qb@gmail.com
     * @return xml file
     */
    public function secondForward($callerId,$busyRec=''){
        $this->viewBuilder()->setLayout('xml-layout');      
        $dialCallStatus = $_POST['DialCallStatus'];
        $this->set(compact('dialCallStatus','callerId','busyRec'));
    }
     /**
     * second forward method
     * @author nobi / giangnt.qb@gmail.com
     * @return xml file
     */
    public function missService($busyRec=''){
        $this->viewBuilder()->setLayout('xml-layout');
        $dialCallStatus = $_POST['DialCallStatus'];
        $this->set(compact('dialCallStatus','busyRec'));
    }

     /**
     * pause call
     * @author nobi / giangnt.qb@gmail.com
     * @return xml file
     */
    public function changeCallState(){
        $this->autoRender = false;
        $callId=$this->request->data['callid'];
       
        require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
        $Users=TableRegistry::get('User');
        $user = $Users->get($this->request->session()->read('uid'));
        $client = new Services_Twilio($user->accountSid, $user->authToken);
        $child = $client->account->calls->get($callId);
        $parent=$client->account->calls->get($child->parent_call_sid);

        $parent->update(array(
            "Url" => "https://u-you-cojp.xtwo-ssl.jp/call/services/pause/$callId",
            "Method" => "POST"
        ));                     
    }
    
    public function pause($callId){       
        $this->viewBuilder()->setLayout('xml-layout');  
        $this->set(compact('callId'));
    }

    public function waitMusic(){       
        $this->viewBuilder()->setLayout('xml-layout');      
    }

  
     /**
     * change status record file method
     * @author nobi / giangnt.qb@gmail.com
     * 
     */

    public function changeStatusRec($flag){
        $this->autoRender = false;
        
        $Users=TableRegistry::get('User');
        $user = $Users->get($this->request->session()->read('uid'));
        $service = $this->Services->get($user->accountSid);            
       
        
        if($flag==1){
            $service->timeoutRec='';
            $service->timeinRec='';
            $service->busyRec='';
        }else{
            $service->timeoutRec=md5($user->accountSid.'timeoutRec').'.mp3';
            $service->timeinRec=md5($user->accountSid.'timeinRec').'.mp3';
            $service->busyRec=md5($user->accountSid.'busyRec').'.mp3';
        }
        
        $this->Services->save($service) ; 
        $this->Flash->success('Your setting has been saved.');
        $this->redirect(['action' => 'index']);
        
    }

    /**
    * change status record file method
    * @author nobi / giangnt.qb@gmail.com
    * 
    */
    public function deleteRecord($time){
        $this->autoRender = false;
        $Users=TableRegistry::get('User');
        $user = $Users->get($this->request->session()->read('uid'));
        $service = $this->Services->get($user->accountSid);            

        switch ($time) {
            case 'timeout':
                $service->timeoutRec='';
                break;
            case 'timein':
                $service->timeinRec='';
                break;
            default:
                $service->busyRec='';
                break;
        }
        $this->Services->save($service) ; 
        $this->Flash->success('Your file record has been deleted.');
        $this->redirect(['action' => 'index']);
        
    }

}
