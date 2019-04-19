<?php


namespace app\models;


use yii\data\ActiveDataProvider;

class EmailsSearch extends Emails
{
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
                    'answer_method'
                ], 'safe'
            ],
            [
                ['status_id', 'manager_id'], 'integer'
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
            ->where(['mailbox_id' => $this->mailbox_id])
            ->orderBy(['mailbox_id' => SORT_ASC, 'imap_date' => SORT_DESC]);

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
        $query->andFilterWhere(['status_id' => $this->status_id]);
        $query->andFilterWhere(['is_read' => $this->is_read]);
        $query->andFilterWhere(['answer_method' => $this->answer_method]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}