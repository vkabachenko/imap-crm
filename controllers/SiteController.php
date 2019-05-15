<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;
use app\helpers\Array2xml;
use yii\web\UploadedFile;

// Модельки
use app\models\Brand;
use app\models\Mails;
use app\models\Order_status;
use app\models\Sip;
use app\models\Source;
use app\models\Users_status;
use app\models\Employees;
use app\models\Users;
use app\models\Orders;
use app\models\Allf;
use app\models\Index;


class SiteController extends \yii\web\Controller
{

		public function beforeAction($action)
		{
		    if ($action->id == 'setxml') {
		        $this->enableCsrfValidation = false;
		    }
        /*
		    if (Yii::$app->request->get('file_link')){
		$fileName='/web/mp3/1'.Yii::$app->request->get('call_session_id').'.mp3';
		$clear = str_replace("]","",str_replace("[","",str_replace('"',"",Yii::$app->request->get('file_link'))));
		echo copy($clear,realpath(dirname(__FILE__).'/../').$fileName);
		echo dirname(__FILE__);
		echo realpath(dirname(__FILE__).'/../').$fileName;
    		exit;
		    }   */
		//copy("http://pr-monkey.com/work/1smt2/1.mp3",realpath(dirname(__FILE__).'/../').'/web/mp3/1'.Yii::$app->request->get('call_session_id').'.mp3');
		//echo realpath(dirname(__FILE__).'/../').'/web/1'.Yii::$app->request->get('call_session_id').'.mp3';

		    return parent::beforeAction($action);
		}
    /**
     * @inheritdoc
     */
    public function behaviors()
    {


//$datP=mktime($d['hour'],$d['minute'],$d['second'],$d['month'],$d['day'],$d['year']);
//print_r(date("d.m.Y H:i",$datP));

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {


        if (!Yii::$app->user->id) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
        if (Yii::$app->request->get('excel')){
        $model = new Allf();
        $model->excel();
        }else {


        $model = new Index();

        // Обновление записи
        if (Yii::$app->request->get('up_status')){ $model->up_status(); }
        if (Yii::$app->request->get('up_status2')){ $model->up_status2(); }
        if (Yii::$app->request->get('up_status3')){ $model->up_status3(); }

	    return $this->render('index', [
            'model' => $model,
        ]);


        return $this->render('index');
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
    $this->layout = 'login'; // Подгрузка страницы логина

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionGetxml()
    {
		$model = new Allf();
		$restaurant=$model->restaurant();
		$xml = Array2XML::createXML('ФайлОбмена', $restaurant);
		return $xml->saveXML();
    }

    public function actionGetxmlcalls()
    {
		$model = new Allf();
		$restaurant=$model->xmlCals();
		$xml = Array2XML::createXML('ФайлОбмена', $restaurant);
		return $xml->saveXML();
    }

    public function actionSetxml()
    {
    $this->enableCsrfValidation = false;
		$status = 'err';
		$fileName='';
		print_r($_POST);
		print_r($_GET);
		print_r($_FILES);

	$model = new Allf();
	$status=$model->SetXml();


    return $status;
    }


    public function actionOrders()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
        $model = new Orders();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('user_id')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('user_id')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }
        //Изменение статуса
        elseif(Yii::$app->request->get('status') and Yii::$app->request->get('id')){ $model->UpStatus(); }

	    return $this->render('orders', [
            'model' => $model,
        ]);

    }


	public function actionUsers(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
        $model = new Users();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }

        if(Yii::$app->request->get('eid') and Yii::$app->request->post('order_name')){ $model->AddOrder(); }

	    return $this->render('users', [
            'model' => $model,
        ]);
	 }

	public function actionEmployees(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
        $model = new Employees();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('email') and Yii::$app->request->post('pwd')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('email') and Yii::$app->request->post('pwd')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	     return $this->render('employees', [
            'model' => $model
         ]);
	 }

	public function actionOrder_status(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

        $model = new Order_status();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	    return $this->render('order_status', [
            'model' => $model,
        ]);

	 }

	public function actionBrand(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

        $model = new Brand();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	     return $this->render('brand', [
            'model' => $model,
        ]);

	 }
	public function actionMail(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
	     return $this->render('mail');
	 }
	public function actionGet_calls(){
		    $model = new Allf();
			$status=$model->GetCalls();
	     return true;
	 }

	public function actionUsers_status(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

        $model = new Users_status();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	     return $this->render('users_status', [
            'model' => $model,
        ]);

	 }

	public function actionSource(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

        $model = new Source();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	     return $this->render('source', [
            'model' => $model,
        ]);

	 }

	public function actionSip(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
        $model = new Sip();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	     return $this->render('sip', [
            'model' => $model,
        ]);
	 }


	public function actionMails(){
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

        $model = new Mails();

        // Обновление записи
        if(Yii::$app->request->get('eid') and Yii::$app->request->post('name')){ $model->Up(); }
        // Добавление записи
        elseif(Yii::$app->request->post('name')){ $model->Add(); }
        // Удаление записи
        elseif(Yii::$app->request->get('did')){ $model->Del(); }


	     return $this->render('mails', [
            'model' => $model,
        ]);

	 }
   public function actionGethistory()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

		if(Yii::$app->request->get('newpost')){
		$connection = Yii::$app->db;
		$connection->createCommand()->insert('user_history', [
		    'uid' => Yii::$app->request->get('uid'),
		    'type' => 0,
		    'text' => Yii::$app->request->get('newpost'),
		    'date' => time()
		])->execute();
		}

	$rows = (new \yii\db\Query())
    ->select(['*'])
    ->from('user_history')
    ->where('uid='.Yii::$app->request->get('uid'))
    ->all();

        return json_encode($rows);
    }

   public function actionGethistoryorder()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

		if(Yii::$app->request->get('newpost')){
		$connection = Yii::$app->db;
		$connection->createCommand()->insert('order_history', [
		    'uid' => Yii::$app->request->get('uid'),
		    'eid' => Yii::$app->user->id,
		    'type' => 0,
		    'text' => Yii::$app->request->get('newpost'),
		    'date' => time()
		])->execute();
		}

		$rows = (new \yii\db\Query())
	    ->select(['(SELECT name FROM `employees` WHERE employees.id=order_history.eid) as name,order_history.*'])
	    ->from('order_history')
	    ->where('uid='.Yii::$app->request->get('uid'))
	    ->all();

        return json_encode($rows);
    }


   public function actionGetlastcalls()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }

	$rows = (new \yii\db\Query())
    ->select(['*'])
    ->from('calls')
    ->where("calls.date>'".(time()-300)."' and type=0")
    ->orderBy('id DESC')
    ->all();

        return json_encode($rows);
    }

   public function actionUserinfo()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(Url::to(['login']), 301)->send();
        }
        $row=array();
		if(Yii::$app->request->get('tel')){
			$rows = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('users')
		    ->where("users.tel='+".trim(Yii::$app->request->get('tel'))."'")
		    ->all();
		  //  echo Yii::$app->request->get('tel');
			$calls = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('calls')
		    ->where("calls.tel_from='+".trim(Yii::$app->request->get('tel'))."'")
		    ->orderBy('id DESC')
		    ->all();
		    for($i=0;$i<sizeof($calls);$i++){$calls[$i]['datetime']=date("d.m.Y H:i",$calls[$i]['date']);}

		    $row=$rows[0];
		    $row['calls']=$calls;
		}
        return json_encode($row);
    }

}
