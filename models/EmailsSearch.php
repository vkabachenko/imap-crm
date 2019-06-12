<?php


namespace app\models;


use yii\data\ActiveDataProvider;

class EmailsSearch extends Emails
{
    public $fromName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'imap_date',
                    'imap_from',
                    'imap_to',
                    'imap_subject',
                    'comment',
                    'answer_method',
                    'status_id',
                    'manager_id',
                    'imap_raw_content',
                    'fromName'
                ], 'safe'
            ],
            [
                ['is_read'], 'boolean'
            ]
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Emails::find()
            ->with(['emailStatus', 'manager'])
            ->where(['mailbox_id' => $this->mailbox_id, 'is_deleted' => $this->is_deleted])
            ->orderBy(['imap_date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params))) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'imap_date', $this->imap_date]);
        $query->andFilterWhere(['like', 'imap_from', $this->imap_from]);
        $query->andFilterWhere(['like', 'imap_to', $this->imap_to]);
        $query->andFilterWhere(['like', 'imap_subject', $this->imap_subject]);
        $query->andFilterWhere(['like', 'imap_raw_content', $this->imap_raw_content]);

        if ($this->status_id === 'empty') {
            $query->andWhere(['status_id' => null]);
        } else {
            $query->andFilterWhere(['status_id' => $this->status_id]);
        }

        $query->andFilterWhere(['is_read' => $this->is_read]);

        if ($this->answer_method === 'empty') {
            $query->andWhere(['answer_method' => null]);
        } else {
            $query->andFilterWhere(['answer_method' => $this->answer_method]);
        }

        if ($this->manager_id === 'empty') {
            $query->andWhere(['manager_id' => null]);
        } else {
            $query->andFilterWhere(['manager_id' => $this->manager_id]);
        }

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        if (!empty($this->fromName)) {
            $query->andWhere(['like', 'imap_raw_content', '%\"fromName\":\"%' . $this->fromName . '%\"%', false]);
        }


        return $dataProvider;
    }
}