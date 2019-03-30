<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;


class Orders extends Model
{

    // Название таблицы
	public static function tableName()
	{
	    return 'orders';
	}

    // Название страницы
	public static function pageName()
	{
	    return 'orders';
	}

	// Записей на странице
	public static function PL()
	{
	    return 50;
	}

    // Удаление записей
    public function Del()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->delete($this->tableName(), [
		    'id' => Yii::$app->request->get('did')
		])->execute();
		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();

    }


	// Обновление статуса
    public function UpStatus()
    {
		$connection = Yii::$app->db;
		$connection->createCommand()->update($this->tableName(), [
		    'status' => Yii::$app->request->get('status')
		], 'id='.Yii::$app->request->get('id'))->execute();

    }
	// Обновление записей
    public function Up()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->update($this->tableName(), [
		    'user_id' => Yii::$app->request->post('user_id'),
		    'employe_id' => Yii::$app->request->post('employe_id'),
		    'name' => Yii::$app->request->post('name'),
		    'type' => 0,
		    'date' => time(),
		    'brand' => Yii::$app->request->post('brand'),
		    'num' => Yii::$app->request->post('num'),
		    'text' => Yii::$app->request->post('text'),
		    'status' => Yii::$app->request->post('status')
		], 'id='.Yii::$app->request->get('eid'))->execute();
		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();

    }
    // Добавление записей
    public function Add()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->insert($this->tableName(), [
		    'user_id' => Yii::$app->request->post('user_id'),
		    'employe_id' => Yii::$app->request->post('employe_id'),
		    'name' => Yii::$app->request->post('name'),
		    'type' => 0,
		    'date' => time(),
		    'brand' => Yii::$app->request->post('brand'),
		    'num' => Yii::$app->request->post('num'),
		    'text' => Yii::$app->request->post('text'),
		    'gid' => ''
		])->execute();
		$thisOid = Yii::$app->db->getLastInsertID();
		$connection->createCommand()->insert('user_history', [
		    'uid' => Yii::$app->request->post('user_id'),
		    'type' => 1,
		    'text' => 'Добавлен заказ #'.$thisOid,
		    'date' => time()
		])->execute();
		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();


    }
    // Получение записей
    public function GetRows()
    {
		$whr="id>0";
		if(Yii::$app->request->post('sort_date1')){
			$explode_date = explode(".",Yii::$app->request->post('sort_date1')); $date = mktime(0,0,date("s"),$explode_date[1],$explode_date[0],$explode_date[2]);
			$whr.=" and date>='".$date."'";
			}

		        	if(Yii::$app->request->post('sort_num')){$whr.=" and num LIKE '%".Yii::$app->request->post('sort_num')."%'";}

		$whrUser="id>0";
			if(Yii::$app->request->post('sort_tel')){$whrUser.=" and tel LIKE '%".Yii::$app->request->post('sort_tel')."%'";}
				if(Yii::$app->request->post('sort_name')){$whrUser.=" and name LIKE '%".Yii::$app->request->post('sort_name')."%'";}
		$rowsUser = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('users')
		    ->where($whrUser)
		    ->all();
		if(sizeof($rowsUser)>0){
		$strUser="";
		foreach ($rowsUser as $u) {
		    if(!empty($strUser)){$strUser.=' OR ';}
		    $strUser.="user_id='".$u['id']."'";
			}
		$whr.=" and (".$strUser.")";
		}

		$count = (new \yii\db\Query())
		    ->select(['id'])
		    ->from($this->tableName())
		    ->where($whr)
		    ->all();

		$pages = new Pagination(['totalCount' => sizeof($count), 'pageSize' => $this->PL()]);

		$rows = (new \yii\db\Query())
		    ->select(['(SELECT name FROM `order_status` WHERE order_status.id=orders.status) as status_name,(SELECT name FROM `brand` WHERE brand.id=orders.brand) as brand_name,orders.*'])
		    ->from($this->tableName())
		    ->offset($pages->offset)
		    ->limit($pages->limit)
		    ->where($whr)
		    ->orderBy('id DESC')
		    ->all();

        return array('rows'=>$rows,'p'=>$pages);
    }

    // Получение записи
    public function GetRow()
    {
		$rows = (new \yii\db\Query())
		    ->select(['*'])
		    ->from($this->tableName())
		    ->where('id='.Yii::$app->request->get('eid'))
		    ->limit(1)
		    ->all();

        return $rows[0];
    }


    // Получение клиента
    public function GetClient($id)
    {
		$rows = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('users')
		    ->where('id='.$id)
		    ->limit(1)
		    ->all();

        return $rows[0];
    }

    // Получение след. статуса
    public function NextStatus($id)
    {
		$NextStatus = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('order_status')
		    ->where('id='.($id+1))
		    ->limit(1)
		    ->all();

        return $NextStatus;
    }
    // Получение массива для форм
    public function GetArrForForm($table,$firstname)
    {
			$rows = (new \yii\db\Query())->select(['*'])->from($table)->all();
			if(!empty($firstname)){ $arr = array(0=>$firstname); }else {$arr = array();}

			for($i=0;$i<sizeof($rows);$i++){
					$arr[$rows[$i]['id']]=$rows[$i]['name'];
				}
        return $arr;
    }


}