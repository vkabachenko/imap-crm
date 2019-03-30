<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;


class Mails extends Model
{

    // Название таблицы
	public static function tableName()
	{
	    return 'mails';
	}

    // Название страницы
	public static function pageName()
	{
	    return 'mails';
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

		$connection = Yii::$app->db;
		$connection->createCommand()->update($this->tableName(), [
		    'name' => Yii::$app->request->post('name'),
		    'server' => Yii::$app->request->post('server'),
		    'login' => Yii::$app->request->post('login'),
		    'pwd' => Yii::$app->request->post('pwd')
		], 'id='.Yii::$app->request->get('eid'))->execute();

		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();

    }
    // Добавление записей
    public function Add()
    {

		$connection = Yii::$app->db;
		$connection->createCommand()->insert($this->tableName(), [
		    'name' => Yii::$app->request->post('name'),
		    'server' => Yii::$app->request->post('server'),
		    'login' => Yii::$app->request->post('login'),
		    'pwd' => Yii::$app->request->post('pwd')
		])->execute();

		Yii::$app->response->redirect(Url::to([$this->pageName()]), 301)->send();


    }
    // Получение записей
    public function GetRows()
    {
		$count = (new \yii\db\Query())
		    ->select(['id'])
		    ->from($this->tableName())
		    ->all();

		$pages = new Pagination(['totalCount' => sizeof($count), 'pageSize' => $this->PL()]);

		$rows = (new \yii\db\Query())
		    ->select(['*'])
		    ->from($this->tableName())
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

        return $rows[0];
    }

}