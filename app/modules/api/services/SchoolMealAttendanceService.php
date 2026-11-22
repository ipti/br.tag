<?php

/**
 * Grava a presença do dia para a merenda, enviada pelo reconhecimento facial.
 *
 * Regras:
 * - Uma presença por escola e dia. A escola tem que ser a do cliente da API.
 * - Fica gravada a entrega de maior número. Uma entrega menor que a gravada é
 *   ignorada. Uma entrega igual (reenvio depois de falha) substitui a gravada,
 *   então reenviar é seguro.
 * - Alunos que não existem no TAG não são gravados e voltam em
 *   "alunos_ignorados" na resposta. Os demais são gravados normalmente.
 */
class SchoolMealAttendanceService
{
    public const STATUS_SAVED = 'gravado';
    public const STATUS_IGNORED = 'ignorado';

    private const INSERT_CHUNK_SIZE = 500;

    public function save(array $body, ApiClient $client, string $clientSchoolInep): array
    {
        $data = $this->validate($body);

        if ($data['escola_id'] !== $clientSchoolInep) {
            throw new ApiException(
                403,
                'escola_nao_permitida',
                'Este cliente da API só pode enviar a presença da escola ' . $clientSchoolInep . '.'
            );
        }

        [$studentIds, $ignoredIds] = $this->splitKnownStudents($data['aluno_ids']);

        $db = Yii::app()->db;
        $transaction = $db->beginTransaction();
        try {
            $existing = $db->createCommand(
                'SELECT id, delivery FROM school_meal_attendance
                WHERE school_inep_fk = :school AND `date` = :date
                FOR UPDATE'
            )->queryRow(true, [':school' => $data['escola_id'], ':date' => $data['dia']]);

            if ($existing && (int) $existing['delivery'] > $data['entrega']) {
                $transaction->rollback();
                return $this->result(self::STATUS_IGNORED, $data, (int) $existing['delivery'], 0, $ignoredIds);
            }

            $attendance = $existing
                ? SchoolMealAttendance::model()->findByPk($existing['id'])
                : new SchoolMealAttendance();

            $attendance->school_inep_fk = $data['escola_id'];
            $attendance->date = $data['dia'];
            $attendance->delivery = $data['entrega'];
            $attendance->generated_at = $data['gerado_em'];
            $attendance->total = $data['total'];
            $attendance->reviewed = $data['conferida'] ? 1 : 0;
            $attendance->complete = $data['completo'] ? 1 : 0;
            $attendance->without_registration = $data['sem_matricula'];
            $attendance->api_client_fk = $client->id;

            if (!$attendance->save()) {
                throw new ApiException(422, 'dados_invalidos', 'Não foi possível gravar a presença.', $attendance->getErrors());
            }

            SchoolMealAttendanceStudent::model()->deleteAllByAttributes(['school_meal_attendance_fk' => $attendance->id]);
            $this->insertStudents((int) $attendance->id, $studentIds);

            $transaction->commit();
        } catch (CDbException $e) {
            $transaction->rollback();
            if ($this->isDuplicateKey($e)) {
                throw new ApiException(409, 'envio_simultaneo', 'Outra entrega do mesmo dia está sendo gravada. Tente de novo.');
            }
            throw $e;
        } catch (Throwable $e) {
            $transaction->rollback();
            throw $e;
        }

        return $this->result(self::STATUS_SAVED, $data, $data['entrega'], count($studentIds), $ignoredIds);
    }

    /**
     * Valida o corpo da requisição e devolve os campos já normalizados.
     * Junta todos os problemas numa única resposta 422.
     */
    private function validate(array $body): array
    {
        $errors = [];

        $schoolInep = isset($body['escola_id']) && (is_string($body['escola_id']) || is_int($body['escola_id']))
            ? trim((string) $body['escola_id'])
            : '';
        if ($schoolInep === '') {
            $errors['escola_id'] = 'Obrigatório: INEP da escola.';
        }

        $date = $this->parseDate($body['dia'] ?? null);
        if ($date === null) {
            $errors['dia'] = 'Obrigatório, no formato AAAA-MM-DD.';
        }

        $delivery = $this->parseInt($body['entrega'] ?? null, 1);
        if ($delivery === null) {
            $errors['entrega'] = 'Obrigatório: número inteiro a partir de 1.';
        }

        $total = $this->parseInt($body['total'] ?? null, 0);
        if ($total === null) {
            $errors['total'] = 'Obrigatório: número inteiro a partir de 0.';
        }

        $generatedAt = null;
        if (isset($body['gerado_em']) && $body['gerado_em'] !== '') {
            $generatedAt = $this->parseDateTime($body['gerado_em']);
            if ($generatedAt === null) {
                $errors['gerado_em'] = 'Data e hora no formato ISO 8601, ex.: 2026-11-18T09:30:04-03:00.';
            }
        }

        $studentIds = [];
        if (!isset($body['presentes']) || !is_array($body['presentes']) || !array_is_list($body['presentes'])) {
            $errors['presentes'] = 'Obrigatório: lista de alunos presentes (pode ser vazia).';
        } else {
            foreach ($body['presentes'] as $index => $student) {
                $studentId = is_array($student) ? $this->parseInt($student['aluno_id'] ?? null, 1) : null;
                if ($studentId === null) {
                    $errors['presentes[' . $index . '].aluno_id'] = 'Obrigatório: id do aluno no TAG.';
                    continue;
                }
                $studentIds[$studentId] = $studentId;
            }
        }

        $meta = $body['meta'] ?? [];
        if (!is_array($meta)) {
            $errors['meta'] = 'Precisa ser um objeto.';
            $meta = [];
        }

        $reviewed = $this->parseBool($meta['conferida'] ?? false);
        if ($reviewed === null) {
            $errors['meta.conferida'] = 'Precisa ser true ou false.';
        }

        $complete = $this->parseBool($meta['completo'] ?? true);
        if ($complete === null) {
            $errors['meta.completo'] = 'Precisa ser true ou false.';
        }

        $withoutRegistration = $this->parseInt($meta['sem_matricula'] ?? 0, 0);
        if ($withoutRegistration === null) {
            $errors['meta.sem_matricula'] = 'Precisa ser um número inteiro a partir de 0.';
        }

        if (!empty($errors)) {
            throw new ApiException(422, 'dados_invalidos', 'Dados inválidos na presença.', $errors);
        }

        return [
            'escola_id' => $schoolInep,
            'dia' => $date,
            'entrega' => $delivery,
            'gerado_em' => $generatedAt,
            'total' => $total,
            'aluno_ids' => array_values($studentIds),
            'conferida' => $reviewed,
            'completo' => $complete,
            'sem_matricula' => $withoutRegistration,
        ];
    }

    /**
     * Separa os ids de aluno que existem no TAG dos que não existem.
     *
     * @return array{0: int[], 1: int[]}
     */
    private function splitKnownStudents(array $studentIds): array
    {
        if (empty($studentIds)) {
            return [[], []];
        }

        $known = array_map('intval', Yii::app()->db->createCommand()
            ->select('id')
            ->from('student_identification')
            ->where(['in', 'id', $studentIds])
            ->queryColumn());

        $ignored = array_values(array_diff($studentIds, $known));
        sort($ignored);

        return [$known, $ignored];
    }

    private function insertStudents(int $attendanceId, array $studentIds): void
    {
        $builder = Yii::app()->db->getCommandBuilder();
        foreach (array_chunk($studentIds, self::INSERT_CHUNK_SIZE) as $chunk) {
            $rows = array_map(
                static fn (int $studentId): array => [
                    'school_meal_attendance_fk' => $attendanceId,
                    'student_fk' => $studentId,
                ],
                $chunk
            );
            $builder->createMultipleInsertCommand('school_meal_attendance_student', $rows)->execute();
        }
    }

    private function result(string $status, array $data, int $savedDelivery, int $savedStudents, array $ignoredIds): array
    {
        $message = $status === self::STATUS_SAVED
            ? 'Presença gravada.'
            : 'Entrega ignorada: o TAG já tem uma entrega de número maior para este dia.';

        return [
            'status' => $status,
            'mensagem' => $message,
            'escola_id' => $data['escola_id'],
            'dia' => $data['dia'],
            'entrega_recebida' => $data['entrega'],
            'entrega_gravada' => $savedDelivery,
            'alunos_gravados' => $savedStudents,
            'alunos_ignorados' => $ignoredIds,
        ];
    }

    private function parseDate($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }
        $date = DateTime::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value ? $value : null;
    }

    private function parseDateTime($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }
        $dateTime = DateTime::createFromFormat(DateTime::ATOM, $value)
            ?: DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $value);
        if (!$dateTime) {
            return null;
        }
        $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return $dateTime->format('Y-m-d H:i:s');
    }

    private function parseInt($value, int $min): ?int
    {
        if (is_bool($value)) {
            return null;
        }
        $int = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min]]);
        return $int === false ? null : $int;
    }

    private function parseBool($value): ?bool
    {
        return is_bool($value) ? $value : null;
    }

    private function isDuplicateKey(CDbException $e): bool
    {
        return isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062;
    }
}
