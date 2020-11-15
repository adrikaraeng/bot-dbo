<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cases;

/**
 * CasesSearch represents the model behind the search form of `app\models\Cases`.
 */
class CasesSearch extends Cases
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'login', 'kategori', 'sub_kategori', 'backend', 'channel', 'sub_channel'], 'integer'],
            [['nama', 'tiket', 'hp', 'app_version', 'email', 'inet_pstn_track', 'keluhan', 'tanggal_masuk', 'status', 'gambar', 'feedback', 'source', 'source_email', 'urgensi_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Cases::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tanggal_masuk' => $this->tanggal_masuk,
            'login' => $this->login,
            'kategori' => $this->kategori,
            'sub_kategori' => $this->sub_kategori,
            'backend' => $this->backend,
            'channel' => $this->channel,
            'sub_channel' => $this->sub_channel,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'tiket', $this->tiket])
            ->andFilterWhere(['like', 'hp', $this->hp])
            ->andFilterWhere(['like', 'app_version', $this->app_version])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'inet_pstn_track', $this->inet_pstn_track])
            ->andFilterWhere(['like', 'keluhan', $this->keluhan])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'gambar', $this->gambar])
            ->andFilterWhere(['like', 'feedback', $this->feedback])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'source_email', $this->source_email])
            ->andFilterWhere(['like', 'urgensi_status', $this->urgensi_status]);

        return $dataProvider;
    }
}
