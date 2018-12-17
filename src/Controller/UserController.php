<?php
namespace App\Controller;
use App\Controller\AppController;
use Services_Twilio_Capability;
use Services_Twilio;
use neApiClient;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\I18n\Time;
/**
 * User Controller
 *
 *@author nobi / giangnt.qb@gmail.com
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UserController extends AppController
{
    const MAIL_FROM = 'giangnt.qb@gmail.com';
    const APP_NAME = 'Call Center';
    /* main page method
     * @author nobi / giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */
    public function mainPage()
    {
        $this->viewBuilder()->setLayout('default-home');        
    }
    /* public function beforeFilter(Event $event)
    {  
        echo 'please wait'; 
    } */
    /**
     * Index method
     * @author nobi / giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */
    
    public function index()
    {
        
        $this->viewBuilder()->setLayout('default-login-regist');
        if($this->request->session()->read('uid')){           
            $acc = $this->User->get($this->request->session()->read('uid'));
            if($acc->confirm=='1'){
                $this->redirect(['action' => 'work']);
            }else{
                $this->redirect(['action' => 'confirm']);
            }
            
        }
        $user = $this->paginate($this->User);

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        if($this->request->is('post')){
            
            $query = $this->User->find(
                'all', 
                ['conditions' => array(
                    'email' => $this->request->data['mail'],
                    'pass' => md5($this->request->data['password']),
                )]
            );

            if ($query->isEmpty()) {
                $this->Flash->error('Account does not exits. Please check and try again!');
            }else{
                $query=$query->execute()->fetchAll('assoc');
                if($query[0]['User__active']==2){
                    $this->Flash->error('Your account is locked by the admin. Please contact your admin to unlock account.');
                }else{
                    $time=time();
                    $session = $this->request->session();
                    $session->write('uid', $this->request->data['mail']);
                    $session->write('session_login', $time);
                    $this->User->updateAll(
                        array( 'session_login' => $time ),   
                        array( 'email' => $this->request->data['mail'] )  
                    );
                    $this->redirect(['action' => 'index']);
                }
                
            }
        }
    }
     /**
     * reigter method
     * @author nobi / giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */
    public function register()
    {
        $this->viewBuilder()->setLayout('default-login-regist');
        $time=strtotime(date('Y-m-d H:i:s'));     
        $timePlus=date('Y-m-d H:i:s');     
        if ($this->request->is('post')) {
            $user = $this->User->newEntity();
            $user->email = $this->request->data['email'];
            $user->company = $this->request->data['company'];
            $user->name = $this->request->data['name'];
            $user->access = 1;
            $user->image='default_users.png';
            $user->pass = md5($this->request->data['pass']);
            $user->active = 0;
            $user->timeConfirm = date("Y-m-d H:i:s", strtotime("$timePlus + 1 hour"));
            $user->timeActive = date("Y-m-d H:i:s", strtotime("$timePlus + 7 day"));
            $user->confirm=md5($user->email.$time);
            $query = $this->User->find(
                'all', 
                ['conditions' => array(
                    'email' => $this->request->data['email'],
                )]
            );

            if ($query->isEmpty()) {

                if($this->User->save($user)){
                    $this->sendMailConfirm($user->email,$user->name,$user->confirm);
                    $session = $this->request->session();
                    $session->write('uid', $this->request->data['email']);
                    return $this->redirect(['action' => 'index']);                    
                }else{
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                }

            }else{

                $this->Flash->error(__('This account already exists'));

            }
           
        }
    }
     /**
     * reigter method
     * @author nobi
     * @return \Cake\Http\Response|void
     */
    public function home()
    {
        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));
            $this->set('user', $user);
        }else{
            $this->redirect('/User');
        }        
        
    }

     /**
     * reigter method
     * @author nobi
     * @return \Cake\Http\Response|void
     */
    public function logout()
    {
        $session = $this->request->session();
        $session->delete('uid');
        $this->redirect('/User');
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->User->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->User->newEntity();
        if ($this->request->is('post')) {
            $user = $this->User->patchEntity($user, $this->request->getData());
            if ($this->User->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->User->get($id, [
            'contain' => []
        ]);
       
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->User->patchEntity($user, $this->request->getData());
            $time=md5($user->email);
            $path = WWW_ROOT."img/user/"; 
            $name_image = "user_$time.jpg";
            if($user->img['size']>512000){
                $this->redirect(['action'=>'home']);
                $this->Flash->error(__('Image size no more than 500kb! Please check and try again.'));
            }else{
                if($user->img['tmp_name']!=''){
                    
                    if (file_exists($path.$user->image)&& $user->image!='default_users.png'){
                        unlink($path.$user->image);   
                    }
                    move_uploaded_file($user->img['tmp_name'],$path.$name_image);                                           
                    $user->image=$name_image;
                }           

                if ($this->User->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect(['action' => 'home']);
                }

                $this->Flash->error(__('The user could not be saved. Please, try again.'));
                $this->redirect(['action'=>'home']);
            }
            
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->User->get($id);
        if ($this->User->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

     /**
     * Change password method
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws 
     */
    public function changePass()
    {   
        $this->autoRender = false;
        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));
            $this->set('user', $user);

            if ($this->request->is('post')) {
                $data=$this->request->data;

                if(md5($data['oldPassword'])==$user->pass){
                    $user->pass=md5($data['newPassword']);
                    if($this->User->save($user)){
                        $this->Flash->success(__('The password has been changed.'));
                        $this->redirect(['action' => 'home']);
                    }else{
                        $this->Flash->error(__('Please check and try again.'));
                        $this->redirect(['action' => 'home']);
                    }                    
                }else{
                    $this->Flash->error(__('Old password is not true! Please check and try again.'));
                    $this->redirect(['action' => 'home']);
                }
            }


        }else{
            $this->redirect('/User');
        }  
    }

    /**
     * call center action
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return Call log
     * @throws
     */
    public function work()
    {   
        $from=date('Y-m-d 00:00:00');
        $to=date('Y-m-d 23:59:59');
        $clientName = 'Center';
        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));
            if($user->accountSid==''||$user->authToken==''||$user->appSid==''||$user->callerId==''){
                $this->Flash->error(__('You must complete this form before using.'));
                return $this->redirect(['action' => 'home']);                
            }else{
                $callData = TableRegistry::get('Memocaller')->find('all')->where(['date >=' => $from,'date <=' => $to,'accountSid'=>$user->accountSid])->order(['date' => 'DESC'])->execute()->fetchAll('assoc');             
                require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio' . DS . 'Capability.php');
                
                $capability = new Services_Twilio_Capability($user->accountSid, $user->authToken);
                $capability->allowClientOutgoing($user->appSid);
                $capability->allowClientIncoming($clientName);
                $token = $capability->generateToken();
                
              
                $this->set(compact('user','token','clientName','callData'));
            }                

        }else{
            $this->redirect('/User');
        }  
    }

    /**
    * getGuest action test
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */
    public function getGuest()
    {   
        $this->autoRender = false;
        require_once(ROOT . DS. 'vendor' . DS  . 'next-engine' . DS . 'neApiClient.php');
        $today=date('Y-m-d');
        $clientId= 'Q6cjSTAd5E8lrY';
        $clientSecret= 'uj9AfE1XhpqMoswTtSK4BHPkQNzcGgmvVLbyd8DJ';
        $redirectUri= 'https://ntg-center.000webhostapp.com/user/get-guest';
      
        $client = new neApiClient($clientId, $clientSecret, $redirectUri) ;

        $queryr['fields'] = 'receive_order_purchaser_tel,receive_order_purchaser_name,receive_order_id';
        $queryr['receive_order_import_date-gt'] = date('Y-m-d', strtotime("today- 3 day"));
        $queryr['receive_order_purchaser_tel-neq'] = '';
        $guest = $client->apiExecute('/api_v1_receiveorder_base/search', $queryr);
        
        $newGuest = TableRegistry::get('Guest_list');         
        foreach ($guest['data'] as  $value) {
            $guestImport = $newGuest->newEntity();
            $guestImport->num=$value['receive_order_purchaser_tel'];
            $guestImport->name=$value['receive_order_purchaser_name'];
            $guestImport->orderId=$value['receive_order_id'];
            $newGuest->save($guestImport);
        }
        $this->redirect('/User/work');   
    }
    /**
    * show guest data action test
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */
    public function showGuest()
    {   
        $this->autoRender = false;
        $guestList = TableRegistry::get('Guest_list');
        $guest=$guestList->find(
                            'all', 
                            ['conditions' => array(
                                'num' => $this->request->data['num'],
                            )]
                        );
        if($guest->isEmpty()){
            echo 0;
        }else{
            $guestData=$guest->execute()->fetchAll('assoc');
            echo json_encode($guestData[0]);
        }    
    }
    /**
    * save memo action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */
    public function saveMemo()
    {   
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $memocaller = TableRegistry::get('Memocaller');
            $newRecord = $memocaller->newEntity();           
            $newRecord->callId=$this->request->data['callid'];
            $newRecord->memo=$this->request->data['memo'];
            $newRecord->accountSid=$this->request->data['accountSid'];
            $memocaller->save($newRecord); 
            $this->Flash->set(__('Your notes have been saved'));
            if(isset($this->request->data['peopleNumber'])){
                $this->redirect(['controller'=>'memocaller','action' => 'showCallLogByPeople/'.$this->request->data['peopleNumber'],]);
            }else{
                ($this->request->data['returnFlag']==0)?$action='work':$action='showCallLog';
                $this->redirect(['action' => $action]);
            }            
                     
        }          
    }

    /**
    * confirm action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */
    public function confirm($code=null)
    {   
        
        $this->viewBuilder()->setLayout('default-login-regist');
        if($code==null){

            if(!$this->request->session()->read('uid')){
                $this->redirect(['action' => 'index']);
            }else{
                $user = $this->User->get($this->request->session()->read('uid'));
                if($user->confirm==1){
                    $this->redirect(['action' => 'home']);
                }
            }
            
        }else{
            if($this->User->updateAll(
                array( 'confirm' => '1' ),   
                array( 'confirm' => $code )  
            )){
                $this->Flash->success(__('Your account is activated.'));
                if($this->request->session()->read('uid')){
                    $user = $this->User->get($this->request->session()->read('uid'));
                    $this->redirect(['action' => 'home']);
                }else{
                    $this->redirect(['action' => 'index']);
                }
            }else{
                $this->Flash->error(__('Your link is expried. Please register again.'));
                $this->redirect(['action' => 'register']);
                $session = $this->request->session();
                $session->delete('uid');
            }
        }
       
    }

    /**
    * send mail confirm action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws RecordNotFoundException When record not found.
    */

    private function sendMailConfirm($mail, $name, $code)
    {

        $content=$name.' 様<br><br>';
        $content=$content.'この度は、'.self::APP_NAME.' へのお申込み、誠にありがとうございます。 <br>';       
        $content=$content.'<p style="width:100%; max-width:600px;text-align: justify;">下記URLより、本登録をお願い致します。<br>'.'<a style="width:100%; max-width:500px" href="'.'https://ntg-center.000webhostapp.com/user/confirm/'.$code.'">https://ntg-center.000webhostapp.com/user/confirm/'.$code.'</a>'.'</p>';
        $content=$content.'※登録URLが折り返されている場合は、1行につなげてください。<br>※上記アドレスの有効期限は1時間となっております。<br>それ以上経過してしまった場合は、再度仮登録手続きを行ってください。<br><br>---------------------------------';
        $content=$content.'<p>このメールは送信専用です。<br>ご返信いただきましても、対応いたしかねます。<br>お問い合わせは下記リンクよりお願いいたします。<br>URL: https://ntg-center.000webhostapp.com/user/faq</p>';
        $content=$content.'<p>---------------------------------</p>';
        $content=$content.'<p>Hi '.$name.',<p>';
        $content=$content.'<p style="width:100%; max-width:600px;">Your new Call center account is registered to this email address. To continue using your new '.self::APP_NAME.' account, you must verify this email address by clicking on this link below:<br>'.'<a style="width:100%; max-width:600px" href="'.'https://ntg-center.000webhostapp.com/user/confirm/'.$code.'">https://ntg-center.000webhostapp.com/user/confirm/'.$code.'</a>'.'</p>';
        $content=$content.'Thank you,<br>The '.self::APP_NAME.' Team';

        $email = new Email('default');
        $email->setFrom([self::MAIL_FROM => self::APP_NAME])
            ->emailFormat('html')
            ->setTo($mail)
            ->setSubject('Please Verify Your Email With '.self::APP_NAME)
            ->send($content);
    }
    /**
    * send mail confirm employee action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws RecordNotFoundException When record not found.
    */

    private function sendMailConfirmEmployee($mail, $name, $code,$pass)
    {

        $content=$name.' 様<br><br>';
        $content=$content.'この度は、'.self::APP_NAME.' へのお申込み、誠にありがとうございます。 <br>';       
        $content=$content.'<p style="width:100%; max-width:600px;text-align: justify;">下記URLより、本登録をお願い致します。<br>'.'<a style="width:100%; max-width:500px" href="'.'https://ntg-center.000webhostapp.com/user/confirm/'.$code.'">https://ntg-center.000webhostapp.com/user/confirm/'.$code.'</a>'.'</p>';
        $content=$content.'<p style="width:100%; max-width:600px;text-align: justify;">アカウント：<br>メール:<b>'.$mail.'</b><br>パスワード：<b>'.$pass.'</b></p>';
        $content=$content.'※登録URLが折り返されている場合は、1行につなげてください。<br>※上記アドレスの有効期限は1時間となっております。<br>それ以上経過してしまった場合は、再度仮登録手続きを行ってください。<br><br>---------------------------------';
        $content=$content.'<p>このメールは送信専用です。<br>ご返信いただきましても、対応いたしかねます。<br>お問い合わせは下記リンクよりお願いいたします。<br>URL: http://call-center.jp/user/faq</p>';
        $content=$content.'<p>---------------------------------</p>';
        $content=$content.'<p>Hi '.$name.',<p>';
        $content=$content.'<p style="width:100%; max-width:600px;">Your new Call center account is registered to this email address. To continue using your new '.self::APP_NAME.' account, you must verify this email address by clicking on this link below:<br>'.'<a style="width:100%; max-width:600px" href="'.'https://ntg-center.000webhostapp.com/user/confirm/'.$code.'">https://ntg-center.000webhostapp.com/user/confirm/'.$code.'</a>'.'</p>';
        $content=$content.'<p style="width:100%; max-width:600px;text-align: justify;">Your account：<br>Email:<b>'.$mail.'</b><br>Password：<b>'.$pass.'</b></p>';
        $content=$content.'Thank you,<br>The '.self::APP_NAME.' Team';

        $email = new Email('default');
        $email->setFrom([self::MAIL_FROM => self::APP_NAME])
            ->emailFormat('html')
            ->setTo($mail)
            ->setSubject('Please Verify Your Email With '.self::APP_NAME)
            ->send($content);
    }
    /**
    * resend confirm action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */

    public function resendConfirm (){
        $this->autoRender = false;
        $user = $this->User->get($this->request->session()->read('uid'));
        $time=strtotime(date('Y-m-d H:i:s')); 
        $user->confirm=md5($user->email.$time);
        $this->User->save($user);
        $this->sendMailConfirm($user->email,$user->name,$user->confirm);
        $this->Flash->set(__('Email has been resend. Please check email to activate.'));
        $this->redirect(['action' => 'confirm']);
    }

    /**
    * add new employee action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */

    public function employee(){

        if($this->request->session()->read('uid')){
           
            $user = $this->User->get($this->request->session()->read('uid'));
       
            $this->set('user', $user);
            if($user->access==1){
                $emp = $this->User
                ->find('all',['fields' => ['email','name','image','confirm','active']])
                ->where(['accountSid' => $user->accountSid, 'access'=>2])
                ->execute()->fetchAll('assoc');

                $this->set(compact('emp'));    
                if ($this->request->is('post')) {

                    $query = $this->User->find(
                        'all', 
                        ['conditions' => array(
                            'email' => $this->request->data['email'],
                        )]
                    );

                    if($query->isEmpty()){
                        $pass = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 8);
                        $timePlus=date('Y-m-d H:i:s');                               
                        $confirm=md5($this->request->email.strtotime(date('Y-m-d H:i:s')));

                        if($this->addEmployee(
                            $this->request->data['email'],
                            $user->company,
                            $this->request->data['name'],
                            $pass,
                            $user->accountSid,
                            $user->authToken,
                            $user->appSid,
                            $user->callerId,
                            $confirm,
                            $user->active,
                            date("Y-m-d H:i:s", strtotime("$timePlus + 1 hour")),
                            date("Y-m-d H:i:s", strtotime("$timePlus + 7 day"))
                        )==1){
                            $this->sendMailConfirmEmployee($this->request->data['email'],$this->request->data['name'],$confirm,$pass);
                            $this->redirect(['action' => 'index','controller'=>'services']);
                            
                        }else{
                            $this->Flash->error(__('The account could not be saved. Please try again.'));
                        }

                    }else{
                        $this->Flash->error(__('This account already exists. Please check and try again'));
                    }
                    
                }
            }else{
                $this->Flash->error(__('You can not access Employee Manager page.'));
                $this->redirect(['action' => 'home']);
            }        
       
        }else{
            $this->redirect('/User');
        }

    }
     /**
    * add new employee action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */

    private function addEmployee($email,$company,$name,$pass,$accountSid,$authToken,$appSid,$callerId,$confirm,$active,$timeConfirm,$timeActive){

        $newUser = $this->User->newEntity();
        $newUser->email = $email;        
        $newUser->company = $company;        
        $newUser->access = 2;        
        $newUser->name = $name;        
        $newUser->pass = md5($pass);        
        $newUser->image = 'default_users.png';        
        $newUser->accountSid = $accountSid;        
        $newUser->authToken = $authToken;        
        $newUser->appSid = $appSid;        
        $newUser->callerId = $callerId;        
        $newUser->confirm = $confirm;        
        $newUser->active = $active;        
        $newUser->timeConfirm = $timeConfirm;        
        $newUser->timeActive = $timeActive;        
        if($this->User->save($newUser)){
            return 1;
        }else{
            return 0;
        }
    }
    /**
    * set active employee action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws  admin change active employee
    */
    public function setactive(){

        $this->autoRender = false;
        if ($this->request->is('post')) {
            $userTable = TableRegistry::get('User');
            $user = $userTable->get($this->request->data['email']); 

            $user->active = $this->request->data['act'];
            $userTable->save($user);         
        }   
    }
    /**
    * delete employee action
    * @author taito / khanhphuong.bkdn@gmail.com
    * @param string|null 
    * @return void
    * @throws  admin delete employee
    */
    public function deleteEmployee(){
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $userTable = TableRegistry::get('User');
            $user = $userTable->get($this->request->data['email']);
            $userTable->delete($user);         
        }   
    }
    /**
    * save answer action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */
    public function saveAnswer()
    {   
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $memocaller = TableRegistry::get('Memocaller');
            $newRecord = $memocaller->newEntity();           
            $newRecord->callId=$this->request->data['callid'];
            $newRecord->callTo=$this->request->data['mailName'];
            $newRecord->accountSid=$this->request->data['accountSid'];
            $newRecord->answerEmail=$this->request->data['answerEmail'];                                  
            $newRecord->orderId=$this->request->data['ord'];
            if($this->request->data['guest']!=''){
                $newRecord->image=$this->request->data['guest'];
            }            
            $memocaller->save($newRecord);                
        }          
    }
    /**
    * save caller action
    * @author nobi / giangnt.qb@gmail.com
    * @param string|null 
    * @return guest
    * @throws 
    */
    public function saveCaller()
    {   
        $this->autoRender = false;
        $callers = TableRegistry::get('call_out_temp');
        $newRecord = $callers->newEntity();  
        if ($this->request->is('post')) {                    
            $newRecord->call_id=$this->request->data['callid'];
            $newRecord->from_name=$this->request->data['from_name'];
            $newRecord->from_email=$this->request->data['from_email'];
            $newRecord->date=date('Y-m-d');                         
            $callers->save($newRecord);                
        }          
    }

    /**
     * show call log by date
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return Call log
     * @throws
     */
    public function showCallLog($time=null)
    {   
        $today=date('Y-m-d 00:00:00');
        $day = date('w');

        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));           

            if ($this->request->is('post')) {

                $from=date('Y-m-d 00:00:00',strtotime($this->request->data['from']));
                $to=date('Y-m-d 23:59:59',strtotime($this->request->data['to']));
            }else{
                if($time!=null){
                    switch ($time) {
                        case 'd':
                            $from=$today;
                            $to=date('Y-m-d 23:59:59');
                            break;
                        case 'w':
                            $from=date('Y-m-d 00:00:00', strtotime($today.'-'.($day).' days'));
                            $to=date('Y-m-d 23:59:59', strtotime($today.'+'.(6-$day).' days'));                           
                            break;
                        default:
                            $from=date('Y-m-01 00:00:00');
                            $to=date('Y-m-d 23:59:59');
                            break;
                    }
                }else{
                    $to=date('Y-m-d 23:59:59');
                    $from=date("Y-m-d 00:00:00", strtotime("$today - 3 day"));
                }                    

            }
            
            $callData = TableRegistry::get('Memocaller')->find('all')->where(['date >=' => $from,'date <=' => $to,'accountSid'=>$user->accountSid])->order(['date' => 'DESC'])->execute()->fetchAll('assoc');   

                          
            $countCallStatus=  array();
            $countCallStatus['ans-completed']=0;
            $countCallStatus['ans-busy']=0;
            $countCallStatus['ans-no-answer']=0;
            $countCallStatus['call-completed']=0;
            $countCallStatus['call-busy']=0;
            $countCallStatus['call-no-answer']=0;
            
            foreach ($callData as $value) {
                if($value['Memocaller__callFrom']==$user->callerId){

                    if($value['Memocaller__status']=='completed'){
                        $countCallStatus['call-completed']+=1;
                    }elseif($value['Memocaller__status']=='busy'){
                        $countCallStatus['call-busy']+=1;
                    }else{
                        $countCallStatus['call-no-answer']+=1;
                    }

                }else{
                    
                    if($value['Memocaller__status']=='completed'){
                        $countCallStatus['ans-completed']+=1;
                    }elseif($value['Memocaller__status']=='busy'){
                        $countCallStatus['ans-busy']+=1;
                    }else{
                        $countCallStatus['ans-no-answer']+=1;
                    }
                }
            }

            $this->set(compact('user','callData','countCallStatus','from','to'));
            
        }else{
            $this->redirect('/User');
        }  
    }


    /**
     * show record by date
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return Call log
     * @throws
     */
    public function showRecord()
    {   
        $today=date('Y-m-d');
        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));
            if($user->accountSid==''||$user->authToken==''||$user->appSid==''||$user->callerId==''){
                $this->Flash->error(__('You must complete this form before using.'));
                return $this->redirect(['action' => 'home']);                
            }else{                                
                if ($this->request->is('post')) {

                    $from=date('Y-m-d',strtotime($this->request->data['from']));
                    $to=date('Y-m-d',strtotime($this->request->data['to']." + 1day"));
                }else{
                    $to=date("Y-m-d", strtotime("today + 1 day"));
                    $from=date("Y-m-d", strtotime("$today - 3 day"));
                }
                require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
                $client = new Services_Twilio($user->accountSid, $user->authToken);
                $recordingArray = array();
                foreach ($client->account->recordings->getIterator(0, 50, array(
                             "DateCreated>" => $from,   
                             "DateCreated<" => $to,   
                        )) as $recording
                ) {
                    $recordingArray[$recording->sid]['Sid']=$recording->sid;
                    $recordingArray[$recording->sid]['DateCreated']=$recording->date_created;
                    $recordingArray[$recording->sid]['CallSid']=$recording->call_sid;
                    $recordingArray[$recording->sid]['Uri']=$recording->uri;
                    $recordingArray[$recording->sid]['Price']=$recording->price;
                    $recordingArray[$recording->sid]['Duration']=$recording->duration;
               
                }
                $this->set(compact('user','recordingArray'));
            }                

        }else{
            $this->redirect('/User');
        }  
    }
     /**
     * delrecord by date
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return Call log
     * @throws
     */
    public function delRecord($recid)
    {   
        $this->autoRender = false;
        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));                                 
            require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
            $client = new Services_Twilio($user->accountSid, $user->authToken); 
            $client->account->recordings->delete($recid);           
            $this->Flash->success(__('The record has been deleted.'));
            $this->redirect('/User/showRecord');                  
        }else{
            $this->redirect('/User');
        }  
    }

     /**
     * delAllRecord by date
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return Call log
     * @throws
     */
    public function dellAllRecord()
    {   
        $this->autoRender = false;
        if($this->request->session()->read('uid')){
              
            if($this->request->is('post')){
                $user = $this->User->get($this->request->session()->read('uid'));
                require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
               
                $client = new Services_Twilio($user->accountSid, $user->authToken); 
                foreach ($this->request->data['del_flag'] as $value) {

                    $client->account->recordings->delete($value); 
                }
               
                $this->Flash->success(__('The record has been deleted.'));
                $this->redirect('/User/showRecord');
            }
                              
        }else{
            $this->redirect('/User');
        }  
    }

    /**
     * payment action method
     * @author nobi / giangnt.qb@gmail.com
     * @return \Cake\Http\Response|void
     */
    public function payment()
    {
        if($this->request->session()->read('uid')){
            $user = $this->User->get($this->request->session()->read('uid'));            
            $date=date('Y-m-01');
            $last=date('Y-m-t');
           
            require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
            $client = new Services_Twilio($user->accountSid, $user->authToken);

            foreach ($client->account->usage_records->getIterator(0, 50, array(
                    "Category" => "phonenumbers",
                    "StartDate" => $date,
                    "EndDate" => $last,
                    
                )) as $record
            ) {  

                $phonenumbers['usage']=$record->usage;               
                $phonenumbers['price']=$record->price;
                $phonenumbers['price_unit']=$record->price_unit;
                
            }

            foreach ($client->account->usage_records->getIterator(0, 50, array(
                    "Category" => "calls",
                    "StartDate" => $date,
                    "EndDate" => $last,
                    
                )) as $record
            ) {      
                   
                $voiceMinutes['usage']=$record->usage;           
                $voiceMinutes['price']=$record->price;
            }
            
            foreach ($client->account->usage_records->getIterator(0, 50, array(
                    "Category" => "calls-client",
                    "StartDate" => $date,
                    "EndDate" => $last,
                    
                )) as $record
            ) {      
                       
                $callsClient['usage']=$record->usage;               
                $callsClient['price']=$record->price;
            }

            foreach ($client->account->usage_records->getIterator(0, 50, array(
                    "Category" => "recordings",
                    "StartDate" => $date,
                    "EndDate" => $last,
                    
                )) as $record
            ) {      
                       
                $recordings['usage']=$record->usage;               
                $recordings['price']=$record->price;
            }            

            $this->set(compact('user','recordings','callsClient','voiceMinutes','phonenumbers'));
        }else{
            $this->redirect('/User');
        } 
    }
    
    public function report($id = null)
    {
        $user = $this->User->get($this->request->session()->read('uid'));  
        $this->set(compact('user'));
        $statisticBy = $id?$id:'month';
        $endDate = Time::now();
        $startDate = new Time($endDate);

        switch ($statisticBy) {
            case 'week':                                  
                $startDate=date('Y-m-d 00:00:00', strtotime(date('Y-m-d').'-'.(date('w')).' days'));
                break;
            case 'month':
                $startDate = $startDate->day(1);
                break;
            case 'year':
                $startDate = $startDate->month(1)->day(1);
                break;
            default:
                break;
        }
        
        $date =  new Time($startDate);
        $statistic = [
            'callin' => [
                'accept' => 0,
                'cancel' => 0,
                'busy' => 0,
                'time' => 0
            ],
            'callout' => [
                'accept' => 0,
                'cancel' => 0,
                'time' => 0
            ]
        ];
        $logData = [];
        if($statisticBy=='year') {
            for($i = 0;$date->month<=$endDate->month&&$date<=$endDate;$i++) {
                $from = date('Y-m-d 00:00:00',strtotime($date));
                $to = new Time($date);
                $to = $to->modify('last day of this month');
                if($to>$endDate) $to = $endDate;
                $to = date('Y-m-d 23:59:59',strtotime($to));

                $callDataByMonth = TableRegistry::get('Memocaller');
                $callInDataByMonthResult = $callDataByMonth->find(
                    'all', 
                    [
                        'fields'=>[
                            'status'=>'status',
                            'count'=>'COUNT(Memocaller.callId)',
                            'sum'=>'SUM(Memocaller.duration)',
                        ],
                        'conditions' => array(
                            'accountSid' => $user->accountSid,
                            'date >=' => $from,
                            'date <=' => $to,
                            'callFrom !=' => 'Center',
                        ),
                        'group'=> 'status',
                    ]
                )->execute()->fetchAll('assoc');
                $callin=array(
                    'accept' => 0,
                    'cancel' => 0,
                    'busy' => 0,
                    'time' => 0
                );
                $callout=array(
                    'accept' => 0,
                    'cancel' => 0, 
                    'busy' => 0,                  
                    'time' => 0
                );
                foreach ($callInDataByMonthResult as $value) {
                    if($value['status']=='busy'){
                        $callin['busy']=$value['count'];
                    }elseif($value['status']=='completed'){
                        $callin['accept']=$value['count'];
                        $callin['time']=$value['sum'];
                    }else{
                        $callin['cancel']+=$value['count'];
                    }
                }

                $callOutDataByMonthResult = $callDataByMonth->find(
                    'all', 
                    [
                        'fields'=>[
                            'status'=>'status',
                            'count'=>'COUNT(Memocaller.callId)',
                            'sum'=>'SUM(Memocaller.duration)',
                        ],
                        'conditions' => array(
                            'accountSid' => $user->accountSid,
                            'date >=' => $from,
                            'date <=' => $to,
                            'callFrom' => $user->callerId,
                        ),
                        'group'=> 'status',
                    ]
                )->execute()->fetchAll('assoc');

                foreach ($callOutDataByMonthResult as $value) {
                    if($value['status']=='busy'){
                        $callout['busy']=$value['count'];
                    }elseif($value['status']=='completed'){
                        $callout['accept']=$value['count'];
                        $callout['time']=$value['sum'];
                    }else{
                        $callout['cancel']+=$value['count'];
                    }
                }
               
                $statistic['callin']['accept'] += $callin['accept'];
                $statistic['callin']['busy'] += $callin['busy'];
                $statistic['callin']['cancel'] += $callin['cancel'];
                $statistic['callin']['time'] += $callin['time'];
              
                $statistic['callout']['accept'] += $callout['accept'];
                $statistic['callout']['cancel'] += $callout['cancel'];
                $statistic['callout']['time'] += $callout['time'];
                $data = [
                    'date' => date('Y/m/d', strtotime($date)),
                    'callin' => $callin,
                    'callout' => $callout
                ];
                if($date->month==12) {
                    $date = $date->year($date->year+1);
                }
                else {
                    $date = $date->month($date->month+1);
                }
                array_push($logData, $data);
            }
        }
        else {
            for($i = 0;$date<=$endDate;$i++) {
                
                $from = date('Y-m-d 00:00:00',strtotime($date));
                $to = date('Y-m-d 23:59:59', strtotime($date));                       
                $callin=array(
                    'accept' => 0,
                    'cancel' => 0,
                    'busy' => 0,
                    'time' => 0
                );
                $callout=array(
                    'accept' => 0,
                    'cancel' => 0, 
                    'busy' => 0,                  
                    'time' => 0
                );

                $callDataByDay = TableRegistry::get('Memocaller');
                $callInDataByDayResult = $callDataByDay->find(
                    'all', 
                    [
                        'fields'=>[
                            'status'=>'status',
                            'count'=>'COUNT(Memocaller.callId)',
                            'sum'=>'SUM(Memocaller.duration)',
                        ],
                        'conditions' => array(
                            'accountSid' => $user->accountSid,
                            'date >=' => $from,
                            'date <=' => $to,
                            'callFrom !=' => $user->callerId,
                        ),
                        'group'=> 'status',
                    ]
                )->execute()->fetchAll('assoc');
               
                foreach ($callInDataByDayResult as $value) {
                    if($value['status']=='busy'){
                        $callin['busy']=$value['count'];
                    }elseif($value['status']=='completed'){
                        $callin['accept']=$value['count'];
                        $callin['time']=$value['sum'];
                    }else{
                        $callin['cancel']+=$value['count'];
                    }
                }

                $callOutDataByDayResult = $callDataByDay->find(
                    'all', 
                    [
                        'fields'=>[
                            'status'=>'status',
                            'count'=>'COUNT(Memocaller.callId)',
                            'sum'=>'SUM(Memocaller.duration)',
                        ],
                        'conditions' => array(
                            'accountSid' => $user->accountSid,
                            'date >=' => $from,
                            'date <=' => $to,
                            'callFrom' => $user->callerId,
                        ),
                        'group'=> 'status',
                    ]
                )->execute()->fetchAll('assoc');

                foreach ($callOutDataByDayResult as $value) {
                    if($value['status']=='busy'){
                        $callout['busy']=$value['count'];
                    }elseif($value['status']=='completed'){
                        $callout['accept']=$value['count'];
                        $callout['time']=$value['sum'];
                    }else{
                        $callout['cancel']+=$value['count'];
                    }
                }
            
                $statistic['callin']['accept'] += $callin['accept'];
                $statistic['callin']['busy'] += $callin['busy'];
                $statistic['callin']['cancel'] += $callin['cancel'];
                $statistic['callin']['time'] += $callin['time'];
             
                $statistic['callout']['accept'] += $callout['accept'];
                $statistic['callout']['cancel'] += $callout['cancel'];
                $statistic['callout']['time'] += $callout['time'];
                $data = [
                    'date' => date('Y/m/d', strtotime($date)),
                    'callin' => $callin,
                    'callout' => $callout
                ];
                $date = $date->addDay(1);
                array_push($logData, $data);
            }
            
        }
        // echo '<pre>';print_r($logData);echo '</pre>';exit;
        switch ($statisticBy) {
            case 'week':
                $dayInWeek = ['日', '月', '火', '水', '木', '金', '土'];
                $startDate = $dayInWeek[0];
                $endDate = $dayInWeek[date('w', strtotime($endDate))];
                break;
            case 'month':
                $startDate = date('m/d', strtotime($startDate));
                $endDate = date('m/d', strtotime($endDate));
                break;
            case 'year':
                $startDate = date('m月', strtotime($startDate));
                $endDate = date('m月', strtotime($endDate));
                break;
            default:
                break;
        }
        
        $statistic['callin']['total'] = $statistic['callin']['accept']+$statistic['callin']['cancel']+$statistic['callin']['busy'];
        $statistic['callout']['total'] = $statistic['callout']['accept']+$statistic['callout']['cancel'];
        $this->set(compact('startDate', 'endDate', 'logData', 'statisticBy', 'statistic'));
    }
    
    /**
     * report by employee
     * @author 1: nobi / giangnt.qb@gmail.com
     * @author 2: taito / khanhphuong.bkdn@gmail.com
     * @return \Cake\Http\Response|void
     */
    public function reportByEmployee($id = null)
    {
        $user = $this->User->get($this->request->session()->read('uid'));
        $this->set(compact('user'));
        $emps = $this->User
            ->find()
            ->select(['email','name','image'])
            ->where([
                'accountSid' => $user->accountSid, 
                
            ])
            ->execute()->fetchAll('assoc');
        $statisticBy = $id?$id:'month';
        $endDate = Time::now();
        $startDate = new Time($endDate);
        switch ($statisticBy) {
            case 'week':
                if(date('w')!=1) 
                    $startDate=date('Y-m-d 00:00:00', strtotime(date('Y-m-d').'-'.(date('w')).' days'));
                break;
            case 'month':
                $startDate = $startDate->day(1);
                break;
            case 'year':
                $startDate = $startDate->month(1)->day(1);
                break;
            default:
                break;
        }
        $statistic = [
            'callin' => [
                'call' => 0,
                'time' => 0
            ],
            'callout' => [
                'call' => 0,
                'time' => 0
            ]
        ];
        $logData = [];
        foreach ($emps as $key => $emp) {
          
            $data = [
                'employee' => $emp,
                'data' => [],
                'statistic' => []
            ];
            $date =  new Time($startDate);
            if($statisticBy=='year') {
                for($i = 0;$date->month<=$endDate->month&&$date<=$endDate;$i++) {
                    $from = date('Y-m-d 00:00:00',strtotime($date));
                    $to = new Time($date);
                    $to = $to->modify('last day of this month');
                    if($to>$endDate) $to = $endDate;
                    $to = date('Y-m-d 23:59:59',strtotime($to));
                  
                    $callDataByMonth = TableRegistry::get('Memocaller');
                    $callInDataByMonthResult = $callDataByMonth->find(
                        'all', 
                        [
                            'fields'=>[
                                'count'=>'COUNT(Memocaller.callId)',
                                'sum'=>'SUM(Memocaller.duration)',
                            ],
                            'conditions' => array(
                                'accountSid' => $user->accountSid,
                                'date >=' => $from,
                                'date <=' => $to,
                                'answerEmail' => $emp['User__email'],
                                'callFrom !=' => $user->callerId,
                            ),
                            
                        ]
                    )->execute()->fetchAll('assoc');
                    $callOutDataByMonthResult = $callDataByMonth->find(
                        'all', 
                        [
                            'fields'=>[
                                'count'=>'COUNT(Memocaller.callId)',
                                'sum'=>'SUM(Memocaller.duration)',
                            ],
                            'conditions' => array(
                                'accountSid' => $user->accountSid,
                                'date >=' => $from,
                                'date <=' => $to,
                                'answerEmail' => $emp['User__email'],
                                'callFrom' => $user->callerId,
                            ),
                            
                        ]
                    )->execute()->fetchAll('assoc');
                    $callin = [
                        'call' => $callInDataByMonthResult[0]['count'],
                        'time' => ($callInDataByMonthResult[0]['sum'])?$callInDataByMonthResult[0]['sum']:0,
                    ];
                    $callout = [
                        'call' => $callOutDataByMonthResult[0]['count'],
                        'time' => ($callOutDataByMonthResult[0]['sum'])?$callInDataByMonthResult[0]['sum']:0,
                    ];

                    $data['data'][date('Y/m/d', strtotime($date))] = [
                        'callin' => $callin,
                        'callout' => $callout
                    ];
                    if(isset($data['statistic']['callin']['call'])) {
                        $data['statistic']['callin']['call'] += $callin['call'];
                    }
                    else {
                        $data['statistic']['callin']['call'] = $callin['call'];
                    }
                    if(isset($data['statistic']['callin']['time'])) {
                        $data['statistic']['callin']['time'] += $callin['time'];
                    }
                    else {
                        $data['statistic']['callin']['time'] = $callin['time'];
                    }
                    if(isset($data['statistic']['callout']['call'])) {
                        $data['statistic']['callout']['call'] += $callout['call'];
                    }
                    else {
                        $data['statistic']['callout']['call'] = $callout['call'];
                    }
                    if(isset($data['statistic']['callout']['time'])) {
                        $data['statistic']['callout']['time'] += $callout['time'];
                    }
                    else {
                        $data['statistic']['callout']['time'] = $callout['time'];
                    }
                    if($date->month==12) {
                        $date = $date->year($date->year+1);
                    }
                    else {
                        $date = $date->month($date->month+1);
                    }
                }
            }
            else {
                for($i = 0;$date<=$endDate;$i++) {
                    $from = date('Y-m-d 00:00:00',strtotime($date));
                    $to = date('Y-m-d 23:59:59', strtotime($date)); 
                
                    $callDataByDay = TableRegistry::get('Memocaller');
                    $callInDataByDayResult = $callDataByDay->find(
                        'all', 
                        [
                            'fields'=>[
                                'count'=>'COUNT(Memocaller.callId)',
                                'sum'=>'SUM(Memocaller.duration)',
                            ],
                            'conditions' => array(
                                'accountSid' => $user->accountSid,
                                'date >=' => $from,
                                'date <=' => $to,
                                'answerEmail' => $emp['User__email'],
                                'callFrom !=' => $user->callerId,
                            ),
                            
                        ]
                    )->execute()->fetchAll('assoc');

                    $callOutDataByDayResult = $callDataByDay->find(
                        'all', 
                        [
                            'fields'=>[
                                'count'=>'COUNT(Memocaller.callId)',
                                'sum'=>'SUM(Memocaller.duration)',
                            ],
                            'conditions' => array(
                                'accountSid' => $user->accountSid,
                                'date >=' => $from,
                                'date <=' => $to,
                                'answerEmail' => $emp['User__email'],
                                'callFrom' => $user->callerId,
                            ),
                            
                        ]
                    )->execute()->fetchAll('assoc');
                   
                    $callin = [
                        'call' => $callInDataByDayResult[0]['count'],
                        'time' => ($callInDataByDayResult[0]['sum'])?$callInDataByDayResult[0]['sum']:0,
                    ];

                    $callout = [
                        'call' => $callOutDataByDayResult[0]['count'],
                        'time' => ($callOutDataByDayResult[0]['sum'])?$callOutDataByDayResult[0]['sum']:0,
                    ];
                    $data['data'][date('Y/m/d', strtotime($date))] = [
                        'callin' => $callin,
                        'callout' => $callout
                    ];

                    if(isset($data['statistic']['callin']['call'])) {
                        $data['statistic']['callin']['call'] += $callin['call'];
                    }
                    else {
                        $data['statistic']['callin']['call'] = $callin['call'];
                    }
                    if(isset($data['statistic']['callin']['time'])) {
                        $data['statistic']['callin']['time'] += $callin['time'];
                    }
                    else {
                        $data['statistic']['callin']['time'] = $callin['time'];
                    }
                    if(isset($data['statistic']['callout']['call'])) {
                        $data['statistic']['callout']['call'] += $callout['call'];
                    }
                    else {
                        $data['statistic']['callout']['call'] = $callout['call'];
                    }
                    if(isset($data['statistic']['callout']['time'])) {
                        $data['statistic']['callout']['time'] += $callout['time'];
                    }
                    else {
                        $data['statistic']['callout']['time'] = $callout['time'];
                    }
                    $date = $date->addDay(1);
                }
            }
            $statistic['callin']['call'] += $data['statistic']['callin']['call'];
            $statistic['callin']['time'] += $data['statistic']['callin']['time'];
            $statistic['callout']['call'] += $data['statistic']['callout']['call'];
            $statistic['callout']['time'] += $data['statistic']['callout']['time'];
            array_push($logData, $data);
        }
        
        switch ($statisticBy) {
            case 'week':
                $dayInWeek = ['日', '月', '火', '水', '木', '金', '土'];
                $startDate = $dayInWeek[0];
                $endDate = $dayInWeek[date('w', strtotime($endDate))];
                break;
            case 'month':
                $startDate = date('m/d', strtotime($startDate));
                $endDate = date('m/d', strtotime($endDate));
                break;
            case 'year':
                $startDate = date('m月', strtotime($startDate));
                $endDate = date('m月', strtotime($endDate));
                break;
            default:
                break;
        }
        //echo '<pre>';print_r($statistic);echo '</pre>';exit;
        $this->set(compact('startDate', 'endDate', 'logData', 'statisticBy', 'statistic'));
    }

     /**
     * selectNumber. callerid is customer phone number
     * @author nobi / giangnt.qb@gmail.com
     * @param string|null $email User email.
     * @return Call id
     * @throws
     */

    public function getDetails(){
        $this->autoRender = false;
        $user = $this->User->get($this->request->session()->read('uid'));

        $date=date('Y-m-01');
        $last=date('Y-m-t');
        
        require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
        $client = new Services_Twilio($user->accountSid, $user->authToken);
        $details= array();
        foreach ($client->account->usage_records->getIterator(0, 50, array(
                "Category" => "phonenumbers",
                "StartDate" => $date,
                "EndDate" => $last,
                
            )) as $record
        ) {  

            $details['phoneNumberUsage']=$record->usage;
            $details['phoneNumberPrice']=$record->price;            
        }

        foreach ($client->account->usage_records->getIterator(0, 50, array(
                "Category" => "calls",
                "StartDate" => $date,
                "EndDate" => $last,
                
            )) as $record
        ) {      
               
            $details['voiceMinutesUsage']=$record->usage;           
            $details['voiceMinutesPrice']=$record->price;
        }
       
        foreach ($client->account->usage_records->getIterator(0, 50, array(
                "Category" => "calls-client",
                "StartDate" => $date,
                "EndDate" => $last,
                
            )) as $record
        ) {      
                   
            $details['callsClientUsage']=$record->usage;               
            $details['callsClientPrice']=$record->price;
        }

        foreach ($client->account->usage_records->getIterator(0, 50, array(
                "Category" => "recordings",
                "StartDate" => $date,
                "EndDate" => $last,
                
            )) as $record
        ) {

            $details['recordingsUsage']=$record->usage;               
            $details['recordingsPrice']=$record->price;
        }
        $details['sum']=$details['recordingsPrice']+$details['callsClientPrice']+$details['voiceMinutesPrice']+ $details['phoneNumberPrice'];
        echo json_encode($details); 
    }

    /**
     * auto get call log data and insert into database
     * @author nobi / giangnt.qb@gmail.com
     * @param  
     * @return  delete all account no confirm
     * @throws
     */

    public function autoGetCallLogData()
    {
        $this->autoRender = false; 
        $today=date('Y-m-d');  
        $to=date("Y-m-d", strtotime("$today + 1 day"));
        $user = $this->User->get($this->request->session()->read('uid'));
        $callLog = TableRegistry::get('Memocaller');
        $guestName = TableRegistry::get('Guest_list');
        $callOut = TableRegistry::get('call_out_temp');
       
        require_once(ROOT . DS. 'vendor' . DS  . 'twilio-php' . DS . 'Services' . DS . 'Twilio.php');
        $client = new Services_Twilio($user->accountSid, $user->authToken);
        $logData=$client->account->calls->getIterator(0,50,array("StartTime>" => $today, 'StartTime<'=>$to));
      
        $recordingArray = array();
        foreach ($client->account->recordings->getIterator(0, 50, array(
                     "DateCreated>" => $today,   
                )) as $recording
        ) {
            if(isset($recordingArray[$recording->call_sid])){
                $recordingArray[$recording->call_sid].='||'.$recording->uri;
            }else{
                $recordingArray[$recording->call_sid]=$recording->uri;
            }
            
        }

        //echo '<pre>';print_r(date('l',strtotime($today)));echo '</pre>';exit;
        $k=1;
        foreach ($logData as $value) { 
            $newTime=date('Y-m-d H:i:s', strtotime($value->start_time));  
            $h=date('H', strtotime($value->start_time));
            if($value->parent_call_sid!=''|| $h < 10 || $h > 15 ){
               
                $newRecord = $callLog->newEntity();           
                $newRecord->callId=$value->sid;

                if($callLog->exists(['callId' => $value->sid])){
                    $ob=$callLog->get($value->sid);
                    if($ob->callFrom==''){
                        $newRecord->callFrom=$value->from_formatted;
                    }
                    if($ob->callTo==''){
                        $newRecord->callTo=$value->to_formatted;
                    }                
                } else{
                    $newRecord->callFrom=$value->from_formatted;
                    $newRecord->callTo=$value->to_formatted;
                }  

                if (preg_match("/^[\d\+\-\(\) ]+$/", $newRecord->callFrom)) {
                    if($newRecord->callFrom==$user->callerId){
                        if($callOut->exists(['call_id' =>$value->parent_call_sid])){

                            $obCallOut=$callOut->get($value->parent_call_sid);
                            $newRecord->image=$obCallOut->from_name;
                            $newRecord->answerEmail=$obCallOut->from_email;          
                        } 
                    }else{
                        $newNum=str_replace('+81', '0', $newRecord->callFrom);
                    
                        if($guestName->exists(['num' => $newNum])){
                            $obGuest=$guestName->get($newNum);
                            $newRecord->image=$obGuest->name;
                            $newRecord->orderId="1234";
                        }   
                    }
                                     
                    
                }
                if (preg_match("/^[\d\+\-\(\) ]+$/", $newRecord->callTo)) {
                    $newNum=str_replace('+81', '0', $newRecord->callTo);
                   
                    if($guestName->exists(['num' => $newNum])){
                        $obGuest=$guestName->get($newNum);
                        $newRecord->call_to_name=$obGuest->name;
                        $newRecord->orderId="1234";
                    }
                    
                    
                }
                $newRecord->accountSid=$value->account_sid;
                $newRecord->date=$newTime;          
                $newRecord->direction=$value->direction;
                $newRecord->duration=$value->duration;
                $newRecord->status=$value->status;
                if(isset($recordingArray[$value->parent_call_sid])){
                    $newRecord->record=$recordingArray[$value->parent_call_sid];
                } 
                if($h < 10 || $h > 15){
                    $newRecord->status='failed';
                    $newRecord->callTo='Center (時間外)';
                    $newRecord->duration=0;
                }
                $callLog->save($newRecord);
                if($k==1){
                    $k=$value->sid;
                }    
            }                                 
             
        }
        $newest=$callLog->get($k);
        $newest->date=date('m/d H:i', strtotime( $newest->date));
        echo json_encode($newest); 
    }

}
