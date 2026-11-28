<?php

/**
 * Cria (ou atualiza) um usuário de teste para cada perfil do sistema.
 *
 * Só roda em base de desenvolvimento (nome do banco começando com "demo."). É idempotente:
 * pode ser executado quantas vezes for preciso, e só altera usuários "teste_*".
 *
 * Uso (a senha nunca fica no repositório):
 *   php yiic seedtestusers --password=<senha>            (ou TAG_SEED_PASSWORD=<senha>)
 *   php yiic seedtestusers --password=<senha> --reset-password
 *   php yiic seedtestusers --school=<INEP> --dry-run
 *
 * Documentação: docs/seed_usuarios_teste.md
 */
class SeedTestUsersCommand extends CConsoleCommand
{
    private const PREFIX = 'teste_';

    /**
     * Perfis e se o login deles depende de vínculo com escola (users_school).
     * admin, superuser, reader, nutritionist e guardian enxergam todas as escolas ativas
     * (ver UserIdentity::authenticate); os demais precisam de uma escola vinculada.
     */
    private const PROFILES = [
        'superuser' => ['name' => 'Teste Superusuário', 'school' => false],
        'admin' => ['name' => 'Teste Administrador', 'school' => false],
        'manager' => ['name' => 'Teste Gestor', 'school' => true],
        'coordinator' => ['name' => 'Teste Coordenador', 'school' => true],
        'instructor' => ['name' => 'Teste Professor', 'school' => true],
        'reader' => ['name' => 'Teste Leitor', 'school' => false],
        'guardian' => ['name' => 'Teste Responsável', 'school' => false],
        'nutritionist' => ['name' => 'Teste Nutricionista', 'school' => false],
        'foodServiceWorker' => ['name' => 'Teste Merendeira', 'school' => true],
    ];

    public function run($args)
    {
        Yii::import('application.components.auth.TRole'); // o console não importa components/auth

        [$options, $school, $password] = $this->parseArguments($args);

        $database = $this->databaseName();
        if (strpos($database, 'demo.') !== 0) {
            echo "Recusado: este seed só roda em base de desenvolvimento (banco 'demo.*'); banco atual: {$database}.\n";
            return 1;
        }
        if (strlen($password) < 6) {
            echo "Informe a senha com --password=<senha> ou TAG_SEED_PASSWORD (mínimo de 6 caracteres).\n";
            return 1;
        }

        $schoolInep = $this->resolveSchool($school);
        if ($schoolInep === null) {
            echo "Nenhuma escola ativa encontrada; informe uma com --school=<INEP>.\n";
            return 1;
        }

        $dryRun = isset($options['dry-run']);
        $resetPassword = isset($options['reset-password']);
        echo "Base: {$database} | escola dos perfis que exigem vínculo: {$schoolInep}" . ($dryRun ? ' | SIMULAÇÃO' : '') . "\n\n";
        printf("%-20s %-26s %-12s %s\n", 'perfil', 'usuário', 'escola', 'resultado');

        $hasher = new PasswordHasher();
        $transaction = Yii::app()->db->beginTransaction();
        try {
            foreach (self::PROFILES as $role => $profile) {
                $result = $this->seedProfile($role, $profile, $schoolInep, $password, $hasher, $resetPassword, $dryRun);
                printf("%-20s %-26s %-12s %s\n", $role, $this->username($role), $profile['school'] ? $schoolInep : '(todas)', $result);
            }
            $dryRun ? $transaction->rollback() : $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollback();
            echo "\nErro: nada foi gravado. {$e->getMessage()}\n";
            return 1;
        }

        echo "\n" . ($dryRun ? 'Simulação concluída: nada foi gravado.' : 'Pronto.') . " A senha não é exibida nem gravada em arquivo.\n";
        return 0;
    }

    private function seedProfile(string $role, array $profile, string $schoolInep, string $password, PasswordHasher $hasher, bool $resetPassword, bool $dryRun): string
    {
        $db = Yii::app()->db;
        $auth = Yii::app()->authManager;
        $username = $this->username($role);
        $notes = [];

        $user = Users::model()->findByAttributes(['username' => $username]);
        if ($user === null) {
            $user = new Users();
            $user->name = $profile['name'];
            $user->username = $username;
            $user->password = $hasher->bcriptHash($password);
            $user->active = 1;
            $user->save(false);
            $notes[] = 'criado';
        } else {
            $changed = false;
            if ((int) $user->active !== 1) {
                $user->active = 1;
                $changed = true;
            }
            if ($resetPassword) {
                $user->password = $hasher->bcriptHash($password);
                $changed = true;
                $notes[] = 'senha redefinida';
            }
            if ($changed) {
                $user->save(false);
            }
            $notes[] = $changed ? 'atualizado' : 'já existia';
        }

        // exatamente um papel por usuário de teste
        $assigned = array_keys($auth->getAuthAssignments($user->id));
        foreach ($assigned as $other) {
            if ($other !== $role) {
                $auth->revoke($other, $user->id);
                $notes[] = "papel {$other} removido";
            }
        }
        if (!in_array($role, $assigned, true)) {
            $auth->assign($role, $user->id);
            $notes[] = 'papel atribuído';
        }

        if ($profile['school']) {
            $linked = (int) $db->createCommand('SELECT COUNT(*) FROM users_school WHERE user_fk = :u AND school_fk = :s')
                ->queryScalar([':u' => $user->id, ':s' => $schoolInep]);
            if ($linked === 0) {
                $link = new UsersSchool();
                $link->user_fk = $user->id;
                $link->school_fk = $schoolInep;
                $link->save(false);
                $notes[] = 'escola vinculada';
            }
        }

        if ($role === TRole::INSTRUCTOR->value) {
            $notes[] = $this->ensureInstructorRecord($user->id, $schoolInep) ? 'cadastro de professor criado' : 'cadastro de professor já existia';
        }

        return implode(', ', $notes);
    }

    /** Professor precisa do cadastro em instructor_identification ligado ao usuário (users_fk). */
    private function ensureInstructorRecord(int $userId, string $schoolInep): bool
    {
        $existing = InstructorIdentification::model()->findByAttributes(['users_fk' => $userId]);
        if ($existing !== null) {
            return false;
        }
        $instructor = new InstructorIdentification();
        $instructor->name = 'Teste Professor';
        $instructor->school_inep_id_fk = $schoolInep;
        $instructor->users_fk = $userId;
        $instructor->birthday_date = '01/01/1990';
        $instructor->sex = 1;
        $instructor->color_race = 0;
        $instructor->deficiency = 0;
        $instructor->nationality = 1;
        $instructor->edcenso_nation_fk = 76; // Brasil
        $instructor->register_type = 30;
        $instructor->save(false);
        return true;
    }

    private function parseArguments(array $args): array
    {
        $options = [];
        $school = null;
        $password = (string) getenv('TAG_SEED_PASSWORD');
        foreach ($args as $arg) {
            if (strpos($arg, '--password=') === 0) {
                $password = substr($arg, strlen('--password='));
            } elseif (strpos($arg, '--school=') === 0) {
                $school = substr($arg, strlen('--school='));
            } elseif (strpos($arg, '--') === 0) {
                $options[substr($arg, 2)] = true;
            }
        }
        return [$options, $school, $password];
    }

    private function resolveSchool(?string $requested): ?string
    {
        $db = Yii::app()->db;
        if ($requested !== null && $requested !== '') {
            $found = $db->createCommand('SELECT inep_id FROM school_identification WHERE inep_id = :i')->queryScalar([':i' => $requested]);
            return $found === false ? null : (string) $found;
        }
        $first = $db->createCommand('SELECT inep_id FROM school_identification WHERE situation = 1 ORDER BY inep_id LIMIT 1')->queryScalar();
        return $first === false ? null : (string) $first;
    }

    private function databaseName(): string
    {
        return (string) Yii::app()->db->createCommand('SELECT DATABASE()')->queryScalar();
    }

    private function username(string $role): string
    {
        return self::PREFIX . strtolower($role);
    }

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic seedtestusers --password=<senha> [--school=<INEP>] [--reset-password] [--dry-run]

DESCRIPTION
  Cria um usuário "teste_<perfil>" para cada um dos 9 perfis (TRole), com o papel atribuído,
  vínculo com uma escola nos perfis que exigem (manager, coordinator, instructor e
  foodServiceWorker) e o cadastro de professor para o instructor. Idempotente.
  Só roda em base "demo.*". A senha vem de --password ou de TAG_SEED_PASSWORD e nunca é gravada.
  Veja docs/seed_usuarios_teste.md.
EOD;
    }
}
