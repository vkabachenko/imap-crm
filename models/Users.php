<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;


class Users extends Model
{

    // Название таблицы
	public static function tableName()
	{
	    return 'users';
	}

    // Название страницы
	public static function pageName()
	{
	    return 'users';
	}

	// Записей на странице
	public static function PL()
	{
	    return 10;
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

	// Добавление заказа
    public function AddOrder()
    {
		$thisUid = Yii::$app->request->get('eid');
		$connection = Yii::$app->db;
		$connection->createCommand()->insert('orders', [
		    'user_id' => $thisUid,
		    'employe_id' => Yii::$app->request->post('order_employe_id'),
		    'name' => Yii::$app->request->post('order_name'),
		    'type' => 0,
		    'date' => time(),
		    'brand' => Yii::$app->request->post('order_brand'),
		    'num' => Yii::$app->request->post('order_num'),
		    'text' => Yii::$app->request->post('order_text'),
		    'gid' => ''
		])->execute();

		$thisOid = Yii::$app->db->getLastInsertID();
		$connection->createCommand()->insert('user_history', [
		    'uid' => $thisUid,
		    'type' => 1,
		    'text' => 'Добавлен заказ #'.$thisOid,
		    'date' => time()
		])->execute();


    }

	// Обновление записей
    public function Up()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->update($this->tableName(), [
		    'name' => Yii::$app->request->post('name'),
		    'tel' => Yii::$app->request->post('tel'),
		    'email' => Yii::$app->request->post('email'),
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
		    'name' => Yii::$app->request->post('name'),
		    'tel' => Yii::$app->request->post('tel'),
		    'email' => Yii::$app->request->post('email'),
		    'text' => Yii::$app->request->post('text'),
		    'status' => Yii::$app->request->post('status'),
		    'date' => time(),
		    'gid' => ''
		])->execute();
		$thisUid = Yii::$app->db->getLastInsertID();
		if(Yii::$app->request->post('order_name')){
		$connection = Yii::$app->db;
		$connection->createCommand()->insert('orders', [
		    'user_id' => $thisUid,
		    'employe_id' => Yii::$app->request->post('order_employe_id'),
		    'name' => Yii::$app->request->post('order_name'),
		    'type' => 0,
		    'date' => time(),
		    'brand' => Yii::$app->request->post('order_brand'),
		    'num' => Yii::$app->request->post('order_num'),
		    'text' => Yii::$app->request->post('order_text'),
		    'gid' => ''
		])->execute();

		$thisOid = Yii::$app->db->getLastInsertID();
		$connection->createCommand()->insert('user_history', [
		    'uid' => $thisUid,
		    'type' => 1,
		    'text' => 'Добавлен заказ #'.$thisOid,
		    'date' => time()
		])->execute();

		}

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
			if(Yii::$app->request->post('sort_tel')){$whr.=" and tel LIKE '%".Yii::$app->request->post('sort_tel')."%'";}
				if(Yii::$app->request->post('sort_name')){$whr.=" and name LIKE '%".Yii::$app->request->post('sort_name')."%'";}


		$count = (new \yii\db\Query())
		    ->select(['id'])
		    ->from($this->tableName())
		    ->where($whr)
		    ->all();

		$pages = new Pagination(['totalCount' => sizeof($count), 'pageSize' => $this->PL()]);

		$sort = 'id DESC';

		if(Yii::$app->request->get('sort') and Yii::$app->request->get('sort2')){ $sort = Yii::$app->request->get('sort').' '.Yii::$app->request->get('sort2'); }

		$rows = (new \yii\db\Query())
		    ->select(['*'])
		    ->from($this->tableName())
		    ->where($whr)
		    ->offset($pages->offset)
		    ->limit($pages->limit)
		    ->orderBy($sort)
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

}