<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
use yii\web\UploadedFile;

class Allf extends Model
{
	// Обновление записей
    public function GetCalls()
    {
		if(Yii::$app->request->get('contact_phone_number') and Yii::$app->request->get('virtual_phone_number') and !Yii::$app->request->get('file_link') and !Yii::$app->request->get('type')){
		$type=0;

		$phone_from=Yii::$app->request->get('contact_phone_number');
		$p1 = substr($phone_from, 0, 1); $p2 = substr($phone_from, 1, 3); $p3 = substr($phone_from, 4, 3); $p4 = substr($phone_from, 7, 2); $p5 = substr($phone_from, 9, 2);
		$phone_from_clean = '+'.$p1.' ('.$p2.') '.$p3.'-'.$p4.'-'.$p5;

		$phone_to=Yii::$app->request->get('virtual_phone_number');
		$p1 = substr($phone_to, 0, 1); $p2 = substr($phone_to, 1, 3); $p3 = substr($phone_to, 4, 3); $p4 = substr($phone_to, 7, 2); $p5 = substr($phone_to, 9, 2);
		$phone_to_clean = '+'.$p1.' ('.$p2.') '.$p3.'-'.$p4.'-'.$p5;

		$status= Yii::$app->request->get('status');

		if ($status === 'start') {
            //$rest = substr(Yii::$app->request->get('contact_phone_number'), 0, 4);
            $contact_id = Yii::$app->request->get(); if(!$contact_id){$contact_id=0;}

            $type=0;

            $connection = Yii::$app->db;
            $connection->createCommand()->insert('calls', [
                'tel_from' => $phone_from_clean,
                'tel_to' => $phone_to_clean,
                'file' => '',
                'date' => time(),
                'sid' => Yii::$app->request->get('call_session_id'),
                'type' => $type,
                'status' => 0,
                'contact_id' => '',
                'sip' => '',
                'time' => ''
            ])->execute();

            $user = (new \yii\db\Query())
                ->select(['id'])
                ->from('users')
                ->where("tel='".$phone_from_clean."'")
                ->all();
            if($user[0]['id']>0){
                $connection->createCommand()->insert('user_history', [
                    'uid' => $user[0]['id'],
                    'type' => 0,
                    'text' => 'Звонок от '.$phone_from_clean,
                    'date' => time()
                ])->execute();
        }

		}
		    if ($status) {
                $model = new RecentCalls([
                    'sid' => Yii::$app->request->get('call_session_id'),
                    'tel_from' => $phone_from_clean,
                    'tel_to' => $phone_to_clean,
                    'date' => Yii::$app->request->get('notification_time'),
                    'sip' => Yii::$app->request->get('sip'),
                    'status' => $status
                ]);
                if (!$model->save()) {
                    Yii::error($model->getErrors(), 'calls');
                }
            }
		}


		// Enoiayuea caiiee

		if(Yii::$app->request->get('contact_phone_number') and Yii::$app->request->get('virtual_phone_number') and !Yii::$app->request->get('file_link') and Yii::$app->request->get('type')){
		$type=0;

		$phone_from=Yii::$app->request->get('contact_phone_number');
		$p1 = substr($phone_from, 0, 1); $p2 = substr($phone_from, 1, 3); $p3 = substr($phone_from, 4, 3); $p4 = substr($phone_from, 7, 2); $p5 = substr($phone_from, 9, 2);
		$phone_from_clean = '+'.$p1.' ('.$p2.') '.$p3.'-'.$p4.'-'.$p5;

		$phone_to=Yii::$app->request->get('virtual_phone_number');
		$p1 = substr($phone_to, 0, 1); $p2 = substr($phone_to, 1, 3); $p3 = substr($phone_to, 4, 3); $p4 = substr($phone_to, 7, 2); $p5 = substr($phone_to, 9, 2);
		$phone_to_clean = '+'.$p1.' ('.$p2.') '.$p3.'-'.$p4.'-'.$p5;

		$contact_id = Yii::$app->request->get(); if(!$contact_id){$contact_id=0;}

		$type=1;

		$connection = Yii::$app->db;
		$connection->createCommand()->insert('calls', [
		    'tel_from' => $phone_from_clean,
		    'tel_to' => $phone_to_clean,
		    'file' => '',
		    'date' => time(),
		    'sid' => Yii::$app->request->get('call_session_id'),
		    'type' => $type,
		    'status' => 0,
		    'contact_id' => '',
		    'sip' => '',
		    'time' => ''
		])->execute();

		}


		if(Yii::$app->request->get('file_link')){

		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => false
			]
		]);

		$contact_id = Yii::$app->request->get('phone'); if(!$contact_id){$contact_id=0;}
		$fileName='/mp3/'.Yii::$app->request->get('call_session_id').'.mp3';
		$clear = str_replace("\\","",str_replace("]","",str_replace("[","",str_replace('"',"",Yii::$app->request->get('file_link')))));
		copy($clear,realpath(dirname(__FILE__).'/../web/').$fileName,$context);

        echo $clear.' -> '.realpath(dirname(__FILE__).'/../').$fileName;
		$phone_to=$contact_id;
		$p1 = substr($phone_to, 0, 1); $p2 = substr($phone_to, 1, 3); $p3 = substr($phone_to, 3, 3); $p4 = substr($phone_to, 6, 2); $p5 = substr($phone_to, 8, 2);
		$phone_to_clean = '+'.$p1.' ('.$p2.') '.$p3.'-'.$p4.'-'.$p5;

		$connection = Yii::$app->db;
		$connection->createCommand()->update('calls', [
		    'file' => $fileName,
		    'contact_id' => $contact_id,
		    'sip' => Yii::$app->request->get('sip'),
		    'time' => Yii::$app->request->get('time')
		], 'sid='.Yii::$app->request->get('call_session_id'))->execute();
			}
    }

	// Обновление записей
    public function SetXml()
    {
		$file = UploadedFile::getInstanceByName('file');
		if($file->name){

			$file->saveAs('excel/'.$file->name); $fileName=$file->name; $status = 'ok';

			$simple = file_get_contents('excel/'.$fileName);
			$movies = simplexml_load_string($simple);

			//print_r($movies->Пользователи[5]);
			$Users=$movies->Контрагенты;
		    $Brand = $movies->пмБренды;
		    $Status = $movies->пмСтатусыСервиса;
		    $Orders = $movies->пмДокументСервиса;
		    $employees=$movies->Пользователи;


		    $connection = Yii::$app->db;

		//Наименование="тест1 тест" ТелефонКонтрагента="8-926-224-36-74"
        foreach ($Brand as $key => $b) {

		$BrandDb = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('brand')
		    ->where("name='".$b['Наименование']."'")
		    ->all();
		if(sizeof($BrandDb)==0){
		$connection->createCommand()->insert('brand', [
		    'name' => $b['Наименование']
		])->execute();
			}

        }


        foreach ($employees as $key => $e) {

		$employeesDb = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('employees')
		    ->where("name='".$e['Наименование']."'")
		    ->all();
		if(sizeof($employeesDb)==0){
		$connection->createCommand()->insert('employees', [
		    'name' => $e['Наименование'],
		    'tel' => '',
		    'email' => '',
		    'rule' => '',
		    'date' => time(),
		    'pwd' => '',
		    'guid' => $e['GUID']
		])->execute();
			}

        }


		$usersO=array();
		$usersOId=array();
		// Обновление пользователей.
		foreach ($Users as $key => $u) {
		$add=0;
		$ctel = str_replace("+","",str_replace(")","",str_replace("(","",str_replace("-","",$u['ТелефонКонтрагента']))));
		$phone_from=$ctel;
		$p1 = substr($phone_from, 0, 1); $p2 = substr($phone_from, 1, 3); $p3 = substr($phone_from, 4, 3); $p4 = substr($phone_from, 7, 2); $p5 = substr($phone_from, 9, 2);
		$phone_from_clean = '+'.$p1.' ('.$p2.') '.$p3.'-'.$p4.'-'.$p5;
		$usersDb = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('users')
		    ->where("name='".$u['Наименование']."' and tel='".$phone_from_clean."'")
		    ->all();
		   // echo "Привет name='".$u['Наименование']."'";
		   // print_r($usersDb);
		for($i=0;$i<sizeof($usersDb);$i++){
		$usersO[]=$usersDb[$i]['id']; $usersOId[] = $u['GUID']; $add=1;

			}
		if(empty($add)){


		$connection->createCommand()->insert('users', [
		    'name' => $u['Наименование'],
		    'tel' => $phone_from_clean,
		    'email' => $u['EmailКонтрагента'],
		    'text' => '',
		    'status' => 0,
		    'gid' => $u['GUID']
		])->execute();
		$thisId=Yii::$app->db->getLastInsertID();
		   $usersO[]=$thisId;
		   $usersOId[] = $u['GUID'];
			}
		}


		// Обновление заказов.
		foreach ($Orders as $key => $u) {

		    $comments = $u->ТаблицаКомментариев;
		    //exit;

		$getbrand = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('brand')
		    ->where("name='".$u['Бренд']."'")
		    ->limit(1)
		    ->all();

		$getStatus = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('order_status')
		    ->where("name='".$u['СтатусРемонта']."'")
		    ->limit(1)
		    ->all();


		if(empty($getbrand[0]['id'])){$getbrand[0]['id']=0;}
		$getOrder = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('orders')
		    ->where("num='".$u['Номер']."'")
		    ->limit(1)
		    ->all();

		if(sizeof($getOrder)>0){
		//print_r($getOrder[0]['id']);exit;
		$connection->createCommand()->update('orders', [
		    //'date' => $u['ДатаПринятияВРемонт'],
		    'brand' => $getbrand[0]['id'],
		    'num' => $u['Номер'],
		    'text' => $u['Оборудование'],
		    'gid' => $u['GUID']
		], 'id='.$getOrder[0]['id'])->execute();

		if(!empty($getStatus[0]['id'])){
		$connection->createCommand()->update('orders', [
		    'status' => $getStatus[0]['id']
		], 'id='.$getOrder[0]['id'])->execute();
			}

		$thisOrderId=$getOrder[0]['id'];


									}else {
								//$userId=0;
								//echo ($usersOId[0]);  echo $u['Контрагент'];
								//print_r($usersOId);
								//print_r($usersO);
								for($qq=0;$qq<sizeof($usersO);$qq++){
								$th = (string)$u['Контрагент'];
								$th2 = (string)$usersOId[$qq];
								// $th.'=='.$th2.'-'.$usersO[$qq];
									if($th==$th2){echo 1;$usd=$usersO[$qq]; break;}
									}


								//if(!empty($usersO[$u['Контрагент']])){$userId = $usersO[$u['Контрагент']];}

								//echo ' _'.$usd; exit;
								$date = $u['ДатаПринятияВРемонт'];
								$d=date_parse_from_format("dmYHis", $date);
								$datP=mktime($d['hour'],$d['minute'],$d['second'],$d['month'],$d['day'],$d['year']);

								$connection->createCommand()->insert('orders', [
								    'user_id' => $usd,
								    'employe_id' => 0,
								    'name' => '',
								    'type' => 0,
								    'date' => $datP,
								    'brand' => $getbrand[0]['id'],
								    'num' => $u['Номер'],
								    'text' => $u['Оборудование'],
								    'gid' => $u['GUID']
								])->execute();
								$thisOrderId=Yii::$app->db->getLastInsertID();
								    		}

								if(!empty($thisOrderId)){
								$connection->createCommand()->delete('order_history', [
								    'uid' => $thisOrderId
								])->execute();

										foreach ($comments as $key2 => $c) {

												// Пользователь
														$getemployees = (new \yii\db\Query())
														    ->select(['*'])
														    ->from('employees')
														    ->where("guid='".$c['Автор']."'")
														    ->limit(1)
														    ->all();
										$date = $c['ДатаВремя'];
										$d=date_parse_from_format("dmYHis", $date);
										$datP=mktime($d['hour'],$d['minute'],$d['second'],$d['month'],$d['day'],$d['year']);
											$connection->createCommand()->insert('order_history', [
											    'uid' => $thisOrderId,
											    'eid' => $getemployees[0]['id'],
											    'type' => 0,
											    'text' => $c['ТекстКомментария'],
											    'date' => $datP
											])->execute();
										}
									}
								}

								}
    }
	// Обновление записей
    public function excel()
    {
		        $str='Клиент;Телефон;Источник;Телефон;Дата и время';
		        //print_r($_SESSION); exit;
		$calls = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('calls')
		    ->orderBy('id DESC')
		    ->where(Yii::$app->session->get('calls'))
		    ->all();

		foreach ($calls as $v) {

		$user = (new \yii\db\Query())
		    ->select(['name'])
		    ->from('users')
		    ->where("tel='".$v['tel_from']."'")
		    ->all();

		$sorce = (new \yii\db\Query())
		    ->select(['name'])
		    ->from('source')
		    ->where("tel='".$v['tel_to']."'")
		    ->all();

		$str .= '
		'.$user[0]['name'].'; '.$v['tel_from'].';'.$sorce[0]['name'].';'.$v['tel_to'].';'.date("d.m.Y H:i",$v['date']).'';

		}

        $filename = 'excel/rec.csv'; $handle = fopen($filename, 'w');	fwrite($handle,  iconv("utf-8", "windows-1251", $str));	fclose($handle);
		header('Content-type: application/csv'); header('Content-Disposition: attachment; filename="'.$filename.'"');
  		readfile($filename); unlink($filename);
    }

	// Обновление записей
    public function xmlCals()
    {
			$restaurant = array();

			$restaurant =  //empty node with attributes
			    array( '@attributes' => array(
			        'ДатаВыгрузки' => date("dmYHis")
			    ));
			$restaurant =  //empty node with attributes
			    array( '@attributes' => array(
			        'ДатаВыгрузки' => date("dmYHis")
			    ));

			$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('calls')
			    ->orderBy('id DESC')
			    ->limit(300)
			    ->all();

			foreach ($rows as $key => $value) {
			//$t='Входящий';
			if($value['type']==0){$t='Входящий'; }else{$t='Исходящий'; }

			$users = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('users')
			    ->where("tel='".$value['tel_from']."'")
			    ->limit(1)
			    ->all();
            $user=$users[0];
			$restaurant['Звонки'][$key] = array();
			$restaurant['Звонки'][$key]['@attributes'] = array(
			    'ID' => $value['id'],
			    'Ктозвонит' => $value['tel_from'],
			    'Кудазвонит' => $value['tel_to'],
			    'Запись' => $value['file'],
			    'Сип' => $value['sip'],
			    'Тип' => $t,
			    'Статус' => $value['status'],
			    'Секунд' => $value['time'],
			    'Дата' => date("dmYHis",$value['date']),
			    'ФИО' => $user['name'],
			    'email' => $user['email'],
			    'Комментарий' => $user['text']
			);



			}

			return $restaurant;
    }
	// Обновление записей
    public function restaurant()
    {
			$restaurant = array();

			$restaurant =  //empty node with attributes
			    array( '@attributes' => array(
			        'ДатаВыгрузки' => date("dmYHis")
			    ));

			//<Пользователи Наименование="Шличенко Виктор Владимирович" GUID="44dd804d-4f97-11e4-83ea-e03f490fad0b"/>
			//<Пользователи Наименование="Праздников Ахтям Нурмухамедович" GUID="defa7f62-fa14-11e3-8652-e03f490fad0b"/>
			//<Пользователи Наименование="Костюк Василий" GUID="abfd00a6-9478-11e5-8e2e-e03f490fad0b"/>

			$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('employees')
			    ->orderBy('id DESC')
			    ->all();

			foreach ($rows as $key => $value) {
			$restaurant['Пользователи'][$key] = array();
			$restaurant['Пользователи'][$key]['@attributes'] = array(
			    'Наименование' => $value['name'],
			    'GUID' => $value['gid'],
			    'ID' => $value['id']
			);

			}
			/*
			$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('users')
			    ->orderBy('id DESC')
			    ->all();

			foreach ($rows as $key => $value) {
			$restaurant['Контрагенты'][$key] = array();
			$restaurant['Контрагенты'][$key]['@attributes'] = array(
			    'Наименование' => $value['name'],
			    'ТелефонКонтрагента' => $value['tel'],
			    'EmailКонтрагента' => $value['email'],
			    'GUID' => '',
			    'ID' => $value['id']
			);

			}
			*/

			$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('brand')
			    ->orderBy('id DESC')
			    ->all();

			foreach ($rows as $key => $value) {
			$restaurant['пмБренды'][$key] = array();
			$restaurant['пмБренды'][$key]['@attributes'] = array(
			    'Наименование' => $value['name'],
			    'ID' => $value['id']
			);

			}

			$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('order_status')
			    ->where("t=1")
			    ->orderBy('id DESC')
			    ->all();

			foreach ($rows as $key => $value) {
			$restaurant['пмСтатусыСервиса'][$key] = array();
			$restaurant['пмСтатусыСервиса'][$key]['@attributes'] = array(
			    'Наименование' => $value['name'],
			    'ID' => $value['id']
			);

			}

			$rows = (new \yii\db\Query())
			    ->select(['(SELECT t FROM `order_status` WHERE order_status.id=orders.status) as order_t,(SELECT name FROM `order_status` WHERE order_status.id=orders.status) as status_name,(SELECT name FROM `brand` WHERE brand.id=orders.brand) as brand_name,orders.*'])
			    ->from('orders')
			    ->orderBy('id DESC')
			    ->all();

			foreach ($rows as $key => $value) {
			if($value['order_t']==1){
				$rowsHistory = (new \yii\db\Query())
			    ->select(['(SELECT name FROM `employees` WHERE employees.id=order_history.eid) as name,order_history.*'])
			    ->from('order_history')
			    ->where('uid='.$value['id'])
			    ->all();
			    $a=array();
			    foreach ($rowsHistory as $keyH => $valueH) {
			    $a[]['@attributes'] = array('НомерСтроки' => $valueH['id'], 'Автор' => $valueH['name'], 'ДатаВремя' => date("dmYHis",$valueH['date']), 'ТекстКомментария' => $valueH['text']);
			    }
			//$restaurant['пмДокументСервиса'][] = array();  05 11 4158 88071858
			$rdate=''; if(!empty($value['date'])){ $rdate = date("dmYHis",$value['date']);  }
			$restaurant['пмДокументСервиса'][]= array('@attributes' => array(
				'GUID' => $value['gid'],
			    'Номер' => $value['num'],
			    'ДатаПринятияВРемонт' => $rdate,
			    'Оборудование' => $value['text'],
			    'СтатусРемонта' => $value['status_name'],
			    'Бренд' => $value['brand_name'],
			    'ДополнительныеОтметки' => ''
			    ),
			    'ТаблицаКомментариев' =>$a
			);                                          // http://f1.s.qip.ru/cuDY8xE8.png

			//$restaurant['пмДокументСервиса'][]['ТаблицаКомментариев'] =array(
			//    '@attributes' => array(
			//        'info' => 'HiRes Logo',
			///        'height' => '300',
			//        'width' => '300',
			//       'url' => 'http://www.example.com/res/hires_logo.png'
			//    )
			//	);
				}
			}
   	     return $restaurant;
    }



}
