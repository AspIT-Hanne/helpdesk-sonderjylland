<?php 

function getTicketNotes($ticketId = null)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    try {
        // Saml data med joins fra de forskellige tabeller
        
        $table = "note";
        $rows = "note.id, note.created_at, note.created_by, note.ticket_id, note.notetext, note.location_id, users.username AS username";
        $join = "LEFT JOIN users ON note.created_by = users.id";

        if ($ticketId !== null) {
            $where = "note.ticket_id = {$ticketId}";
        }

        $result = $dbcon->getDataWithJoinsWhere($table, $rows, $join, $where);
        
        return $result;

    } catch (Exception $e) {
        die($e->getMessage());
    }

}

?>