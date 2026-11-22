<?php

/**
 * Credencial de um aparelho ou sistema externo que usa a API do TAG.
 *
 * @property int $id
 * @property string $name
 * @property string $client_id
 * @property string $client_secret_hash
 * @property string|null $school_inep_fk
 * @property int $active
 * @property string|null $last_used_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolIdentification|null $school
 */
class ApiClient extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'api_client';
    }

    public function rules()
    {
        return [
            ['name, client_id, client_secret_hash', 'required'],
            ['name', 'length', 'max' => 150],
            ['client_id', 'length', 'max' => 64],
            ['client_id', 'unique'],
            ['client_secret_hash', 'length', 'max' => 255],
            ['school_inep_fk', 'length', 'max' => 8],
            ['active', 'boolean'],
            ['last_used_at', 'safe'],
        ];
    }

    public function relations()
    {
        return [
            'school' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'],
        ];
    }

    public function verifySecret(string $secret): bool
    {
        return password_verify($secret, $this->client_secret_hash);
    }

    /**
     * Cria um cliente novo e devolve [cliente, secret em texto]. O secret só
     * existe em texto neste momento: no banco fica apenas o hash.
     *
     * @return array{0: ApiClient, 1: string}
     */
    public static function createWithSecret(string $name, ?string $schoolInep): array
    {
        $secret = bin2hex(random_bytes(32));

        $client = new self();
        $client->name = $name;
        $client->client_id = 'tag_' . bin2hex(random_bytes(12));
        $client->client_secret_hash = password_hash($secret, PASSWORD_DEFAULT);
        $client->school_inep_fk = $schoolInep;
        $client->active = 1;

        return [$client, $secret];
    }
}
