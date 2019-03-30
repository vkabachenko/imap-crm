<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;


class Index extends Model
{

    // Название таблицы
	public static function tableName()
	{
	    return 'brand';
	}

    // Название страницы
	public static function pageName()
	{
	    return 'brand';
	}

	// Записей на странице
	public static function PL()
	{
	    return 10;
	}

	// Обновление записей
    public function up_status()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->update('calls', [
		    'status' => 1
		], 'id='.Yii::$app->request->get('up_status'))->execute();


    }

	// Обновление записей
    public function up_status2()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->update('calls', [
		    'status' => 0
		], 'id='.Yii::$app->request->get('up_status2'))->execute();

		Yii::$app->response->redirect(Url::to([$this->pageName(), 'call_status' => Yii::$app->request->get('call_status') ]), 301)->send();


    }
    // Добавление записей
    public function up_status3()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->update('calls', [
		    'status' => 1
		], 'id='.Yii::$app->request->get('up_status3'))->execute();

		Yii::$app->response->redirect(Url::to([$this->pageName(), 'call_status' => Yii::$app->request->get('call_status') ]), 301)->send();


    }
    // Получение записей
    public function GetRows()
    {
		$whr='id>0';
		if(Yii::$app->request->get('call_status')==1){$whr.=" and type='0'";}
		if(Yii::$app->request->get('call_status')==2){$whr.=" and type='1'";}
		if(Yii::$app->request->get('call_status')==3){$whr.=" and file='' and date<'".(time()-300)."'";}

		if(Yii::$app->request->get('call_status')==4){$whr.=" and status='1'";}
		if(Yii::$app->request->get('call_status')==5){$whr.=" and status='0'";}

		if(Yii::$app->request->post('sort_date1')){
			$explode_date = explode(".",Yii::$app->request->post('sort_date1')); $date = mktime(0,0,date("s"),$explode_date[1],$explode_date[0],$explode_date[2]);
			$whr.=" and date>='".$date."'";
			}

		if(Yii::$app->request->post('sort_date2')){
			$explode_date = explode(".",Yii::$app->request->post('sort_date2')); $date = mktime(0,0,date("s"),$explode_date[1],$explode_date[0],$explode_date[2]);
			$whr.=" and date<='".$date."'";
			}
			if(Yii::$app->request->post('sort_tel')){$whr.=" and tel_from LIKE '%".Yii::$app->request->post('sort_tel')."%'";}


		$count = (new \yii\db\Query())
		    ->select(['id'])
		    ->from('calls')
		    ->where($whr)
		    ->all();

		$pages = new Pagination(['totalCount' => sizeof($count), 'pageSize' => 30]);

		$calls = (new \yii\db\Query())
		    ->select(['*'])
		    ->from('calls')
		    ->orderBy('id DESC')
		    ->offset($pages->offset)
		    ->limit($pages->limit)
		    ->where($whr)
		    ->all();
		$_SESSION['calls']= $whr;
      // echo $_SESSION['calls'];
        return array('calls'=>$calls,'p'=>$pages);
    }

    // Получение записи
    public function GetUser($tel_from)
    {
		$rows = (new \yii\db\Query())
		    ->select(['name'])
		    ->from('users')
		    ->where("tel='".$tel_from."'")
		    ->all();

        return $rows;
    }

    // Получение записи
    public function Getsorce($tel_to)
    {
		$rows = (new \yii\db\Query())
		    ->select(['name'])
		    ->from('source')
		    ->where("tel='".$tel_to."'")
		    ->all();

        return $rows;
    }

    // Получение записи
    public function Getsip($sip)
    {
		$rows = (new \yii\db\Query())
		    ->select(['name'])
		    ->from('sip')
		    ->where("num='".$sip."'")
		    ->all();

        return $rows;
    }

}