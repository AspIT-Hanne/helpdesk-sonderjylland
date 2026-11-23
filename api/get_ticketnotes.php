<?php
    require_once __DIR__ . '/../includes/phpsession.php';

    header('Content-Type: application/json; charset=utf-8');

    // Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$ticketId = $transferdata['ticketId'] ?? null;

    // $input = json_decode(file_get_contents('php://input'), true);
    // $ticketId = filter_var($input['ticketId'] ?? null, FILTER_VALIDATE_INT);

    if (!$ticketId) {
        http_response_code(400);
        echo json_encode(['error' => 'Ugyldigt sags-ID', 'ticketId' => $transferdata['ticketId']]);
        exit;
    }

    try {
        $notes = getTicketNotes($ticketId);
        echo json_encode([
            'notes' => is_array($notes) ? $notes : []
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Kunne ikke hente noter', 'notes' => []]);
    }

    function getTicketNotes($ticketId = null)
    {
        global $dbcon;
        require_once __DIR__ . '/../includes/phpheader.php';

        try {
            // Saml data med joins fra de forskellige tabeller
            $table = "note";
            $rows = "note.id, note.created_at, note.created_by, note.ticket_id, note.notetext, note.location_id, users.username AS username";
            $join = "LEFT JOIN users ON note.created_by = users.id";
            $where = $ticketId !== null ? "note.ticket_id = " . (int)$ticketId : "1=1";

            $result = $dbcon->getDataWithJoinsWhere($table, $rows, $join, $where);

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }