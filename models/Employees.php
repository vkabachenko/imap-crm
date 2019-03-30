<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
/**
Изменить пароль
 *
 */
class Employees extends Model
{

	public static function tableName()
	{
	    return 'employees';
	}

	// Название страницы
	public static function pageName()
	{
	    return 'employees';
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

	// Обновление записей
    public function Up()
    {

		$rul='';
		for($i=0;$i<sizeof($_POST['rul']);$i++){
			if(!empty($_POST['rul'][$i])){
		if(!empty($rul)){$rul.=',';}
		$rul.=$_POST['rul'][$i];
			 	}
			}

		$connection = Yii::$app->db;
		$connection->createCommand()->update($this->tableName(), [
		    'name' => Yii::$app->request->post('name'),
		    'tel' => Yii::$app->request->post('tel'),
		    'email' => Yii::$app->request->post('email'),
		    'rule' => $rul,
		    'pwd' => md5(Yii::$app->request->post('pwd'))
		], 'id='.Yii::$app->request->get('eid'))->execute();

		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();

    }
    // Добавление записей
    public function Add()
    {
		$rul='';
		for($i=0;$i<sizeof($_POST['rul']);$i++){
			if(!empty($_POST['rul'][$i])){
		if(!empty($rul)){$rul.=',';}
		$rul.=$_POST['rul'][$i];
			 	}
			}

			$connection = Yii::$app->db;
			$connection->createCommand()->insert($this->tableName(), [
		    'name' => Yii::$app->request->post('name'),
		    'tel' => Yii::$app->request->post('tel'),
		    'email' => Yii::$app->request->post('email'),
		    'rule' => $rul,
		    'date' => time(),
		    'pwd' => md5(Yii::$app->request->post('pwd')),
		    'guid' => ''
			])->execute();

		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();
    }
    // Получение записей
    public function GetRows()
    {
		$whr="id>0";
		if(Yii::$app->request->post('sort_tel')){$whr.=" and tel LIKE '%".Yii::$app->request->post('sort_tel')."%'";}
		if(Yii::$app->request->post('sort_name')){$whr.=" and name LIKE '%".Yii::$app->request->post('sort_name')."%'";}

		$count = (new \yii\db\Query())
		    ->select(['id'])
		    ->from($this->tableName())
		    ->where($whr)
		    ->all();

		$pages = new Pagination(['totalCount' => sizeof($count), 'pageSize' => $thisPagelmit]);

		$rows = (new \yii\db\Query())
		    ->select(['*'])
		    ->from($this->tableName())
		    ->where($whr)
		    ->offset($pages->offset)
		    ->limit($pages->limit)
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
        $rows[0]['this_rule']=explode(",",$rows[0]['rule']);
        return $rows[0];
    }

    // Массив правил
    public function GetRuleArr()
    {

		$rule_arr = array();

		$rule_arr['name'][] = 'Рабочий стол';
		$rule_arr['id'][] = 1;

		$rule_arr['name'][] = ' -- Статистика сотрудников';
		$rule_arr['id'][] = 1.1;
		$rule_arr['name'][] = ' -- Последние звонки';
		$rule_arr['id'][] = 1.2;
		$rule_arr['name'][] = ' -- Показатели';
		$rule_arr['id'][] = 1.3;


		$rule_arr['name'][] = 'Заказы';
		$rule_arr['id'][] = 2;

		$rule_arr['name'][] = ' -- Добавление';
		$rule_arr['id'][] = 2.1;
		$rule_arr['name'][] = ' -- Редактирование';
		$rule_arr['id'][] = 2.2;
		$rule_arr['name'][] = ' -- Удаление';
		$rule_arr['id'][] = 2.3;
		$rule_arr['name'][] = ' -- Изменение статуса';
		$rule_arr['id'][] = 2.4;

		$rule_arr['name'][] = 'Клиенты';
		$rule_arr['id'][] = 3;

		$rule_arr['name'][] = ' -- Добавление';
		$rule_arr['id'][] = 3.1;
		$rule_arr['name'][] = ' -- Редактирование';
		$rule_arr['id'][] = 3.2;
		$rule_arr['name'][] = ' -- Удаление';
		$rule_arr['id'][] = 3.3;
		$rule_arr['name'][] = ' -- Изменение статуса';
		$rule_arr['id'][] = 3.4;

		$rule_arr['name'][] = 'Почта';
		$rule_arr['id'][] = 4;


  			return $rule_arr;
    }

}