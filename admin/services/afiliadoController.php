<?php
// admin/services/afiliadoController.php
include_once 'database.php'; // Certifique-se de que o caminho esteja correto

/**
 * Retorna as estatísticas do usuário para o painel.
 *
 * @param int $user_id
 * @return array
 */
 
 function getTotalSaldoAfiliados() {
    global $mysqli;
    $query = "SELECT SUM(saldo_afiliados) AS total FROM usuarios";
    $result = mysqli_query($mysqli, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ? (float)$row['total'] : 0;
    }
    return 0;
}
 
function getTotalSacado($user_id) {
    global $mysqli;
    $query = "SELECT SUM(valor) AS total FROM solicitacao_saques WHERE id_user = ? AND status = 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return $total ? (float)$total : 0;
}
 
 
function getUserStats($user_id) {
    global $mysqli;
    $stats = array();

    // 1. Comissão Disponível (comissões) – soma dos depósitos feitos pelo próprio usuário
    $queryDeposits = "SELECT SUM(valor) AS total_deposits 
                      FROM transacoes 
                      WHERE usuario = $user_id 
                        AND status = 'pago'
                        AND tipo = 'deposito'";
    $resultDeposits = mysqli_query($mysqli, $queryDeposits);
    if ($resultDeposits && mysqli_num_rows($resultDeposits) > 0) {
        $row = mysqli_fetch_assoc($resultDeposits);
        $stats['total_deposits'] = $row['total_deposits'] ? $row['total_deposits'] : 0;
    } else {
        $stats['total_deposits'] = 0;
    }

    // 2. Membros Diretos (Indicações)
    // Recupera o invite_code do usuário atual
    $queryInvite = "SELECT invite_code FROM usuarios WHERE id = $user_id LIMIT 1";
    $resultInvite = mysqli_query($mysqli, $queryInvite);
    if ($resultInvite && mysqli_num_rows($resultInvite) > 0) {
        $row = mysqli_fetch_assoc($resultInvite);
        $invite_code = $row['invite_code'];
    } else {
        $invite_code = '';
    }
    $invite_code_escaped = mysqli_real_escape_string($mysqli, $invite_code);
    $queryIndications = "SELECT COUNT(*) AS total_indications 
                         FROM usuarios 
                         WHERE invitation_code = '$invite_code_escaped'";
    $resultIndications = mysqli_query($mysqli, $queryIndications);
    if ($resultIndications && mysqli_num_rows($resultIndications) > 0) {
        $row = mysqli_fetch_assoc($resultIndications);
        $stats['total_indications'] = $row['total_indications'] ? $row['total_indications'] : 0;
    } else {
        $stats['total_indications'] = 0;
    }

    // 3. Primeiros Depósitos: Quantos usuários indicados já fizeram pelo menos um depósito (distinct)
    $queryDepositantes = "SELECT COUNT(DISTINCT t.usuario) AS total_depositantes 
                          FROM transacoes t 
                          JOIN usuarios u ON t.usuario = u.id 
                          WHERE u.invitation_code = '$invite_code_escaped'
                            AND t.status = 'pago'
                            AND t.tipo = 'deposito'";
    $resultDepositantes = mysqli_query($mysqli, $queryDepositantes);
    if ($resultDepositantes && mysqli_num_rows($resultDepositantes) > 0) {
        $row = mysqli_fetch_assoc($resultDepositantes);
        $stats['total_depositantes'] = $row['total_depositantes'] ? $row['total_depositantes'] : 0;
    } else {
        $stats['total_depositantes'] = 0;
    }

    // 4. Qtd de Depósitos: Total de transações de depósito realizadas pelos usuários indicados
    $queryQtdDepositos = "SELECT COUNT(*) AS qtd_depositos 
                          FROM transacoes t 
                          JOIN usuarios u ON t.usuario = u.id 
                          WHERE u.invitation_code = '$invite_code_escaped'
                            AND t.status = 'pago'
                            AND t.tipo = 'deposito'";
    $resultQtdDepositos = mysqli_query($mysqli, $queryQtdDepositos);
    if ($resultQtdDepositos && mysqli_num_rows($resultQtdDepositos) > 0) {
        $row = mysqli_fetch_assoc($resultQtdDepositos);
        $stats['qtd_depositos'] = $row['qtd_depositos'] ? $row['qtd_depositos'] : 0;
    } else {
        $stats['qtd_depositos'] = 0;
    }

    // 5. Valor Depositado: Soma dos valores de depósito realizados pelos usuários indicados
    $queryValorDepositado = "SELECT SUM(t.valor) AS valor_depositado 
                             FROM transacoes t 
                             JOIN usuarios u ON t.usuario = u.id 
                             WHERE u.invitation_code = '$invite_code_escaped'
                               AND t.status = 'pago'
                               AND t.tipo = 'deposito'";
    $resultValorDepositado = mysqli_query($mysqli, $queryValorDepositado);
    if ($resultValorDepositado && mysqli_num_rows($resultValorDepositado) > 0) {
        $row = mysqli_fetch_assoc($resultValorDepositado);
        $stats['valor_depositado'] = $row['valor_depositado'] ? $row['valor_depositado'] : 0;
    } else {
        $stats['valor_depositado'] = 0;
    }

    // 6. Desempenho: Cálculo entre membros diretos e quantidade de depósitos
    // Exemplo: desempenho = (qtd_depositos / total_indications) * 100, se total_indications > 0
    if ($stats['total_indications'] > 0) {
        $stats['desempenho'] = ($stats['qtd_depositos'] / $stats['total_indications']) * 100;
    } else {
        $stats['desempenho'] = 0;
    }

    // 7. Sub-afiliados: Contabiliza os usuários indicados pelos membros diretos do usuário atual.
    // Primeiro, recupera os invite_code dos membros diretos.
    $queryDirect = "SELECT invite_code FROM usuarios WHERE invitation_code = '$invite_code_escaped'";
    $resultDirect = mysqli_query($mysqli, $queryDirect);
    $directInviteCodes = [];
    if ($resultDirect && mysqli_num_rows($resultDirect) > 0) {
        while ($row = mysqli_fetch_assoc($resultDirect)) {
            // Cada membro direto possui seu próprio invite_code
            $directInviteCodes[] = "'" . mysqli_real_escape_string($mysqli, $row['invite_code']) . "'";
        }
    }
    if (count($directInviteCodes) > 0) {
        $directInviteCodesList = implode(',', $directInviteCodes);
        $querySubAfiliados = "SELECT COUNT(*) AS total_sub_afiliados 
                              FROM usuarios 
                              WHERE invitation_code IN ($directInviteCodesList)";
        $resultSubAfiliados = mysqli_query($mysqli, $querySubAfiliados);
        if ($resultSubAfiliados && mysqli_num_rows($resultSubAfiliados) > 0) {
            $row = mysqli_fetch_assoc($resultSubAfiliados);
            $stats['total_sub_afiliados'] = $row['total_sub_afiliados'] ? $row['total_sub_afiliados'] : 0;
        } else {
            $stats['total_sub_afiliados'] = 0;
        }
    } else {
        $stats['total_sub_afiliados'] = 0;
    }

    $stats['total_sacado'] = getTotalSacado($user_id);
    
    
    // 8. Próximo Salário e Data Próximo Salário
$queryProxSalario = "SELECT prox_salario_valor, prox_salario FROM usuarios WHERE id = $user_id LIMIT 1";
$resultProxSalario = mysqli_query($mysqli, $queryProxSalario);
if ($resultProxSalario && mysqli_num_rows($resultProxSalario) > 0) {
    $row = mysqli_fetch_assoc($resultProxSalario);
    $stats['prox_salario_valor'] = $row['prox_salario_valor'] ? $row['prox_salario_valor'] : 0;
    $stats['prox_salario'] = $row['prox_salario'] ? $row['prox_salario'] : '';
} else {
    $stats['prox_salario_valor'] = 0;
    $stats['prox_salario'] = '';
}

$queryComissao = "SELECT comissao_percentual FROM usuarios WHERE id = $user_id LIMIT 1";
    $resultComissao = mysqli_query($mysqli, $queryComissao);
    if ($resultComissao && mysqli_num_rows($resultComissao) > 0) {
        $rowComissao = mysqli_fetch_assoc($resultComissao);
        $stats['comissao_percentual'] = $rowComissao['comissao_percentual'] ? (float)$rowComissao['comissao_percentual'] : 0;
    } else {
        $stats['comissao_percentual'] = 0;
    }

    return $stats;
}



/**
 * Retorna os dados de performance diários para os últimos $days dias.
 *
 * @param int $user_id
 * @param int $days
 * @return array
 */
function getPerformanceData($user_id, $days = 7) {
    global $mysqli;
    
    // Recupera o invite_code do usuário atual
    $queryInvite = "SELECT invite_code FROM usuarios WHERE id = $user_id LIMIT 1";
    $resultInvite = mysqli_query($mysqli, $queryInvite);
    $invite_code = '';
    if ($resultInvite && mysqli_num_rows($resultInvite) > 0) {
        $row = mysqli_fetch_assoc($resultInvite);
        $invite_code = $row['invite_code'];
    }
    $invite_code_escaped = mysqli_real_escape_string($mysqli, $invite_code);
    
    $dates = [];
    $cadastros = [];
    $depositos = [];
    
    // Para cada dia no período
    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        // Exibe o dia da semana ou a data (exemplo: "Seg", "Ter" ou "2025-03-13")
        $dates[] = date('D', strtotime($date)); 
        
        // Cadastros: Contabiliza quantos usuários foram cadastrados (data_cad) com invitation_code igual ao invite_code atual naquele dia
        $queryCadastros = "SELECT COUNT(*) as cnt FROM usuarios 
                           WHERE invitation_code = '$invite_code_escaped' 
                           AND DATE(data_cad) = '$date'";
        $resultCadastros = mysqli_query($mysqli, $queryCadastros);
        $rowCad = mysqli_fetch_assoc($resultCadastros);
        $cadastros[] = isset($rowCad['cnt']) ? (int)$rowCad['cnt'] : 0;
        
        // Depósitos Efetivados: Contabiliza quantos usuários (distintos) indicados efetuaram um depósito naquele dia
        $queryDepositos = "SELECT COUNT(DISTINCT t.usuario) as cnt 
                           FROM transacoes t 
                           JOIN usuarios u ON t.usuario = u.id 
                           WHERE u.invitation_code = '$invite_code_escaped'
                             AND t.status = 'pago'
                             AND t.tipo = 'deposito'
                             AND DATE(t.data_hora) = '$date'";
        $resultDepositos = mysqli_query($mysqli, $queryDepositos);
        $rowDep = mysqli_fetch_assoc($resultDepositos);
        $depositos[] = isset($rowDep['cnt']) ? (int)$rowDep['cnt'] : 0;
    }
    
    return ['dates' => $dates, 'cadastros' => $cadastros, 'depositos' => $depositos];
}



/**
 * Retorna a comissão disponível do usuário (valor da coluna saldo_afiliados).
 *
 * @param int $user_id
 * @return float
 */
function getAvailableCommission($user_id) {
    global $mysqli;
    
    $query = "SELECT saldo_afiliados FROM usuarios WHERE id = $user_id LIMIT 1";
    $result = mysqli_query($mysqli, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['saldo_afiliados'] ? (float)$row['saldo_afiliados'] : 0;
    }
    
    return 0;
}


/**
 * Retorna o invite_code do usuário.
 *
 * @param int $user_id
 * @return string
 */
function getUserInviteCode($user_id) {
    global $mysqli;
    
    $query = "SELECT invite_code FROM usuarios WHERE id = " . (int)$user_id . " LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['invite_code'];
    }
    return '';
}


/**
 * Retorna quantos registros em visita_site (visitantes) o usuário trouxe,
 * considerando o invite_code do usuário e a coluna inviter (na visita_site).
 *
 * @param int $user_id
 * @return int
 */
function getVisitorCount($user_id) {
    global $mysqli;

    $invite_code = getUserInviteCode($user_id); 
    $invite_code_escaped = mysqli_real_escape_string($mysqli, $invite_code);

    $query = "SELECT COUNT(*) AS total_visitas
              FROM visita_site
              WHERE inviter = '$invite_code_escaped'";
    $result = mysqli_query($mysqli, $query);

    $count = 0;
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['total_visitas'] ? (int)$row['total_visitas'] : 0;
    }

    return $count;
}


/**
 * Retorna as últimas transações (com status 'pago') de usuários que foram convidados
 * pelo afiliado atual (via seu invite_code).
 *
 * @param int $user_id
 * @param int $limit Número de registros a exibir (ex: 5)
 * @return array
 */
function getLastCommissions($user_id, $limit = 5) {
    global $mysqli;

    // Pega o invite_code do afiliado atual
    $invite_code = getUserInviteCode($user_id);
    $invite_code_escaped = mysqli_real_escape_string($mysqli, $invite_code);

    // Monta a query para buscar transações recentes
    // Ajuste o SELECT de acordo com as colunas da tabela transacoes e usuarios
    $query = "SELECT 
                  t.data_hora, 
                  t.tipo, 
                  t.status,
                  t.valor,
                  u.mobile AS referido
              FROM transacoes t
              JOIN usuarios u ON t.usuario = u.id
              WHERE u.invitation_code = '$invite_code_escaped'
                AND t.status = 'pago'
              ORDER BY t.data_hora DESC
              LIMIT $limit";

    $result = mysqli_query($mysqli, $query);
    $commissions = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $commissions[] = $row;
        }
    }

    return $commissions;
}


function getDepositosIndiretos($afiliado_id, $status = 'pago') {
    global $mysqli;

    // 1) Pega o invite_code do afiliado
    $queryInvite = "SELECT invite_code
                    FROM usuarios
                    WHERE id = " . (int)$afiliado_id . "
                    LIMIT 1";
    $resultInvite = mysqli_query($mysqli, $queryInvite);
    $afiliadoInviteCode = '';
    if ($resultInvite && mysqli_num_rows($resultInvite) > 0) {
        $row = mysqli_fetch_assoc($resultInvite);
        $afiliadoInviteCode = $row['invite_code'];
    }

    // Se não tiver invite_code, não há indicados
    if (!$afiliadoInviteCode) {
        return [];
    }

    // 2) Buscar todos usuários cujo invitation_code = esse invite_code
    $afiliadoInviteCodeEsc = mysqli_real_escape_string($mysqli, $afiliadoInviteCode);
    $queryIndicados = "SELECT id
                       FROM usuarios
                       WHERE invitation_code = '$afiliadoInviteCodeEsc'";
    $resultIndicados = mysqli_query($mysqli, $queryIndicados);
    $idsIndicados = [];
    if ($resultIndicados && mysqli_num_rows($resultIndicados) > 0) {
        while ($rowInd = mysqli_fetch_assoc($resultIndicados)) {
            $idsIndicados[] = (int)$rowInd['id'];
        }
    }

    // Se nenhum usuário foi indicado, retorna array vazio
    if (empty($idsIndicados)) {
        return [];
    }

    // 3) Montar a lista de IDs para usar no IN (...)
    $listIndicados = implode(',', $idsIndicados);

    // 4) Buscar transacoes com status=pago e transacoes.usuario ∈ (lista de IDs)
    $statusEsc = mysqli_real_escape_string($mysqli, $status);
    $queryTrans = "SELECT t.*, u.mobile AS nome_usuario
                   FROM transacoes t
                   JOIN usuarios u ON t.usuario = u.id
                   WHERE t.usuario IN ($listIndicados)
                     AND t.status = '$statusEsc'
                   ORDER BY t.data_hora DESC";
    $resultTrans = mysqli_query($mysqli, $queryTrans);

    $transacoes = [];
    if ($resultTrans && mysqli_num_rows($resultTrans) > 0) {
        while ($rowT = mysqli_fetch_assoc($resultTrans)) {
            $transacoes[] = $rowT;
        }
    }

    return $transacoes;
}


function getCommissionResume($user_id) {
    // Exemplo mínimo: retorna somente o saldo disponível do usuário
    $resume = [
        'saldo_disponivel' => 0,
        'aprovado_mes' => 0,
        'pendente' => 0,
        'prox_pagamento_dias' => 0
    ];

    // Se tiver a função getAvailableCommission($user_id) que pega do banco:
    $resume['saldo_disponivel'] = getAvailableCommission($user_id);

    // Defina as outras chaves da forma que desejar
    // ...
    // $resume['aprovado_mes'] = ...
    // $resume['pendente'] = ...
    // $resume['prox_pagamento_dias'] = ...
    
    return $resume;
}

function getCommissionHistory($user_id, $limit = 50, $offset = 0) {
    global $mysqli;

    $commissions = [];

    $query = "
        SELECT 
            t.data_hora,
            t.tipo,
            t.status,
            t.comissao,
            t.valor,
            u.mobile AS referido
        FROM transacoes t
        JOIN usuarios u ON t.usuario = u.id
        WHERE t.afiliado_id = $user_id
          AND t.comissao IS NOT NULL
          AND t.comissao > 0
        ORDER BY t.data_hora DESC
        LIMIT $limit OFFSET $offset
    ";

    $result = mysqli_query($mysqli, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $commissions[] = $row;
        }
    }

    return $commissions;
}



/**
 * Retorna uma lista de usuários que foram indicados pelo afiliado atual.
 * Se você quiser filtrar status, soma de depósitos, etc., pode ajustar conforme necessário.
 *
 * @param int $user_id ID do afiliado
 * @return array Lista de usuários com alguns dados
 */
function getReferrals($user_id) {
    global $mysqli;

    // Passo 1: invite_code do afiliado
    $invite_code = getUserInviteCode($user_id);
    if (empty($invite_code)) {
        return [];
    }
    $invEsc = mysqli_real_escape_string($mysqli, $invite_code);

    // Passo 2: Buscar na tabela usuarios
    // Supondo que a tabela tenha colunas: id, nome, data_cad, status_user, last_login, etc.
    $query = "
        SELECT 
            id,
            mobile,
            data_cad
        FROM usuarios
        WHERE invitation_code = '$invEsc'
        ORDER BY data_cad DESC
    ";

    $result = mysqli_query($mysqli, $query);
    $referrals = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Aqui, se quiser calcular soma de depósitos desse referido:
            $userIdRef = (int) $row['id'];
            $querySum = "
                SELECT SUM(valor) as total_depositos
                FROM transacoes
                WHERE usuario = $userIdRef
                  AND status = 'pago'
                  AND tipo = 'deposito'
            ";
            $resSum = mysqli_query($mysqli, $querySum);
            $rowSum = mysqli_fetch_assoc($resSum);
            $row['soma_depositos'] = $rowSum['total_depositos'] ?: 0;

            // Se quiser calcular se ele está “Ativo”, “Pendente”, “Inativo” com base na soma de depósitos, datas, etc.:
            // Exemplo de lógica simples (ajuste conforme sua regra):
            // - se soma_depositos > 0 => Ativo
            // - se soma_depositos == 0 => Pendente
            // - se a data_cad for antiga e sem depósitos => Inativo, etc.
            // (ou use 'status_user' direto do BD, se existir).
            $row['status_calculado'] = 'Ativo';
            if ($row['soma_depositos'] == 0) {
                $row['status_calculado'] = 'Pendente';
            }
            // Se quiser um “Inativo” para quem não loga há X dias, etc., adicione aqui.

            // Adiciona esse usuário ao array final
            $referrals[] = $row;
        }
    }

    return $referrals;
}

/**
 * Retorna um array com estatísticas de referidos:
 * - total
 * - ativos
 * - pendentes
 * - inativos
 *
 * A lógica depende do que você define como “ativo”, “pendente”, etc.
 * Você pode:
 * 1) Consultar getReferrals($user_id)
 * 2) Contar cada status_calculado
 */
function getReferralsStats($user_id) {
    $refs = getReferrals($user_id);

    $stats = [
        'total'    => 0,
        'ativos'   => 0,
        'pendentes'=> 0,
        'inativos' => 0
    ];

    if (!empty($refs)) {
        $stats['total'] = count($refs);

        foreach ($refs as $r) {
            // Se estiver usando 'status_calculado' (como definido acima):
            if ($r['status_calculado'] === 'Ativo') {
                $stats['ativos']++;
            } elseif ($r['status_calculado'] === 'Pendente') {
                $stats['pendentes']++;
            } else {
                // qualquer outro caso => 'Inativo'
                $stats['inativos']++;
            }
        }
    }

    return $stats;
}


/**
 * Classifica o dispositivo em Desktop, Mobile, Tablet, etc.
 * usando strings que aparecem em nav_os ou mac_os.
 */
function classifyDevice($nav_os, $mac_os) {
    $both = strtolower($nav_os . ' ' . $mac_os);

    // Exemplo simples: se tiver “android” ou “iphone” => Mobile,
    // se tiver “windows” ou “mac os x” => Desktop, etc.
    if (strpos($both, 'android') !== false 
        || strpos($both, 'iphone') !== false
        || strpos($both, 'ipad') !== false) {
        return 'Mobile';
    } elseif (strpos($both, 'windows') !== false 
              || strpos($both, 'linux') !== false 
              || strpos($both, 'macintosh') !== false) {
        return 'Desktop';
    } else {
        return 'Outros';
    }
}


/**
 * Conta o total de comissões do afiliado para paginação.
 *
 * @param int $user_id
 * @return int
 */
function getTotalCommissionCount($user_id) {
    global $mysqli;

    $query = "SELECT COUNT(*) AS total FROM transacoes WHERE afiliado_id = $user_id AND comissao IS NOT NULL AND comissao > 0";
    $result = mysqli_query($mysqli, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return (int)$row['total'];
    }

    return 0;
}

?>
