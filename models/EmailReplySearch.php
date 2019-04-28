<?php


namespace app\models;

use yii\data\ActiveDataProvider;

class EmailReplySearch extends EmailReply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'created_at',
                    'from',
                    'to',
                    'subject',
                    'comment',
                ], 'safe'
            ],
            [
                ['manager_id'], 'integer'
            ],
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
        $query = EmailReply::find()
            ->with(['manager'])
            ->where(['mailbox_id' => $this->mailbox_id, 'status' => $this->status])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params))) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'from', $this->from]);
        $query->andFilterWhere(['like', 'to', $this->to]);
        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}