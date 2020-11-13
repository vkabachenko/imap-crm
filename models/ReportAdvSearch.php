<?php

namespace app\models;


use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class ReportAdvSearch extends Model
{
    public $dateBegin;
    public $dateEnd;
    public $tel_from;
    public $tel_to;
    public $campaign;
    public $search_engine;
    public $search_query;
    public $utm_term;


    private function setDates()
    {
        if (!empty($this->dateEnd)) {
            $this->dateEnd = \DateTime::createFromFormat('d-m-Y', $this->dateEnd);
            $this->dateEnd = $this->dateEnd->getTimestamp();
        } else {
            $this->dateEnd = time();
        }

        if (!empty($this->dateBegin)) {
            $this->dateBegin = \DateTime::createFromFormat('d-m-Y', $this->dateBegin);
            $this->dateBegin = $this->dateBegin->getTimestamp();
        } else {
            $this->dateBegin = strtotime('-1 week', $this->dateEnd);
        }
    }

    public function rules()
    {
        return [
            [[
                'dateBegin',
                'dateEnd',
                'tel_from',
                'tel_to',
                'campaign',
                'search_engine',
                'search_query',
                'utm_term'
            ],
                'safe'],
        ];
    }

    public function search($params)
    {
        $this->load($params);

        $this->setDates();

        $query = (new \yii\db\Query())
            ->from('calls')
            ->select(['tel_from', 'tel_to', 'date', 'refs'])
            ->where(['is not', 'refs', null])
            ->andWhere(['between', 'date', $this->dateBegin, $this->dateEnd])
            ->andFilterWhere(['like', 'tel_from', $this->tel_from])
            ->andFilterWhere(['like', 'tel_to', $this->tel_to]);

        if ($this->search_engine) {
            $query->andWhere(['like', 'refs', $this->search_engine]);
        }

        if ($this->search_query) {
            $query->andWhere(['like', 'refs', $this->search_query]);
        }

        if ($this->utm_term) {
            $query->andWhere(['like', 'refs', $this->utm_term]);
        }

        $rows = $query->all();

        foreach ($rows as &$row) {
            $ref = Json::decode($row['refs']);
            $row['campaign'] = isset($ref['campaign_name']) ? $ref['campaign_name'] : '';
            $row['search_engine'] = isset($ref['search_engine']) ? $ref['search_engine'] : '';
            $row['search_query'] = isset($ref['search_query']) ? $ref['search_query'] : '';
            $row['utm_term'] = isset($ref['utm_term']) ? $ref['utm_term'] : '';
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $rows,
            'sort' => [
                'attributes' => [
                    'tel_from', 'tel_to', 'date', 'campaign', 'search_engine', 'search_query', 'utm_term',],
                'defaultOrder' => ['date' => SORT_DESC]
            ]
        ]);

        return $dataProvider;
    }

}