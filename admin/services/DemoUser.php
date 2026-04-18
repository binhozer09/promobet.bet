<?php

class DemoUser
{
    private $conn;

    public function __construct()
    {
        $host = '147.93.33.138';
        $db = 'apiyarkan';
        $user = 'apiyarkan';
        $pass = 'apiyarkan';

        $connStr = "host=$host dbname=$db user=$user password=$pass";

        $this->conn = pg_connect($connStr);

        if (!$this->conn) {
            die(json_encode(['error' => 'Erro ao conectar ao banco de dados']));
        }
    }

    public function setDemo($username, $demo)
    {
        $result = pg_query($this->conn, 'BEGIN');

        if (!$result) {
            return json_encode(['error' => 'Erro ao iniciar transação']);
        }

        try {
            $sql = "UPDATE users SET isinfluencer = $1 WHERE username = $2";
            $stmt = pg_prepare($this->conn, 'update_demo', $sql);

            if (!$stmt) {
                throw new Exception('Erro ao preparar a consulta');
            }

            $result = pg_execute($this->conn, 'update_demo', [$demo, $username]);

            if (!$result) {
                throw new Exception('Erro ao executar a consulta');
            }

            pg_query($this->conn, 'COMMIT');

            $rowsUpdated = pg_affected_rows($result);

            return json_encode([
                'message' => 'RTP atualizado com sucesso',
                'rows_updated' => $rowsUpdated
            ]);
        } catch (Exception $e) {
            pg_query($this->conn, 'ROLLBACK');
            return json_encode(['error' => 'Erro ao atualizar RTP: ' . $e->getMessage()]);
        }
    }

    public function setConfig($agent, $config)
    {
        $result = pg_query($this->conn, 'BEGIN');

        if (!$result) {
            return json_encode(['error' => 'Erro ao iniciar transação']);
        }

        try {
            $sql = "UPDATE agents SET probganho = $1, probbonus = $2, probganhortp = $3, probganhoinfluencer = $4, probbonusinfluencer = $5, probganhoaposta = $6, probganhosaldo = $7, probganhortp70 = $8, probbonusrtp70 = $9 WHERE agentcode = $10";
            $stmt = pg_prepare($this->conn, 'update_config', $sql);

            if (!$stmt) {
                throw new Exception('Erro ao preparar a consulta');
            }

            $probganho = $config['probganho'];
            $probbonus = $config['probbonus'];
            $probganhortp = $config['probganhortp'];
            $probganhoinfluencer = $config['probganhoinfluencer'];
            $probbonusinfluencer = $config['probbonusinfluencer'];
            $probganhoaposta = $config['probganhoaposta'];
            $probganhosaldo = $config['probganhosaldo'];
            $probganhortp70 = $config['probganhortp70'];
            $probbonusrtp70 = $config['probbonusrtp70'];

            $result = pg_execute($this->conn, 'update_config', [$probganho, $probbonus, $probganhortp, $probganhoinfluencer, $probbonusinfluencer, $probganhoaposta, $probganhosaldo, $probganhortp70, $probbonusrtp70, $agent]);

            if (!$result) {
                throw new Exception('Erro ao executar a consulta');
            }

            pg_query($this->conn, 'COMMIT');

            $rowsUpdated = pg_affected_rows($result);

            return json_encode([
                'message' => 'RTP atualizado com sucesso',
                'rows_updated' => $rowsUpdated
            ]);
        } catch (Exception $e) {
            pg_query($this->conn, 'ROLLBACK');
            return json_encode(['error' => 'Erro ao atualizar RTP: ' . $e->getMessage()]);
        }
    }
}