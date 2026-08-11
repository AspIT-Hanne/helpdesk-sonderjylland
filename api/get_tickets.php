<?php 

    require_once __DIR__ . '/../includes/phpheader.php';
    
    function getTicketData()
    {
        global $dbcon;
        
        try {
            // Saml data med joins fra de forskellige tabeller
            
            $table = "tickets";
            $rows = "tickets.*, ticketCategory.name AS category_name, ticketCategory.color AS category_color, ticketStatus.name AS status_name, ticketStatus.color AS status_color, ticketPriority.name AS priority_name, ticketPriority.color AS priority_color, users1.username AS assignedTo_name, users2.username AS createdBy_name";
            $join = "LEFT JOIN ticketCategory ON tickets.ticketCategory_id = ticketCategory.id 
                    LEFT JOIN ticketPriority ON tickets.ticketPriority_id = ticketPriority.id
                    LEFT JOIN ticketStatus ON tickets.ticketStatus_id = ticketStatus.id
                    LEFT JOIN users users1 ON tickets.assigned_to = users1.id
                    LEFT JOIN users users2 ON tickets.created_by = users2.id";

            $result = $dbcon->getDataWithJoins($table, $rows, $join);
            
            return $result;

        } catch (Exception $e) {
            throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
        }

    }

    function getMyTicketData()
    {
        global $dbcon;
        
        try {
            // Saml data med joins fra de forskellige tabeller
            
            $table = "tickets";
            $rows = "tickets.*, ticketCategory.name AS category_name, ticketCategory.color AS category_color, ticketStatus.name AS status_name, ticketStatus.color AS status_color, ticketPriority.name AS priority_name, ticketPriority.color AS priority_color, users1.username AS assignedTo_name, users2.username AS createdBy_name";
            $join = "LEFT JOIN ticketCategory ON tickets.ticketCategory_id = ticketCategory.id 
                    LEFT JOIN ticketPriority ON tickets.ticketPriority_id = ticketPriority.id
                    LEFT JOIN ticketStatus ON tickets.ticketStatus_id = ticketStatus.id
                    LEFT JOIN users users1 ON tickets.assigned_to = users1.id
                    LEFT JOIN users users2 ON tickets.created_by = users2.id";
            $where = "((tickets.created_by = {$_SESSION['userid']} OR tickets.assigned_to = {$_SESSION['userid']})) AND tickets.location_id = {$_SESSION['location_id']}";

            $result = $dbcon->getDataWithJoinsWhere($table, $rows, $join, $where);
            
            return $result;

        } catch (Exception $e) {
            throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
        }
    }

    // Hent brugere med rolle tekniker eller admin til at filtrere sager på forsiden
    function getTechnicians()
    {
        global $dbcon;
        
        try {
            $table = "users";
            $fieldname = "userRole_id";
            $fielddata1 = "2"; // Søger både efter rolle 2 (tekniker) og 3 (admin)
            $fielddata2 = "3";
            $sortBy = "id";

            $result1 = $dbcon->getDataByFieldSorted($table, $fieldname, $fielddata1, $sortBy);
            $result2 = $dbcon->getDataByFieldSorted($table, $fieldname, $fielddata2, $sortBy);

            return array_merge($result1, $result2);
        } catch (Exception $e) {
             throw new Exception("Der blev ikke fundet nogle teknikere." . $e->getMessage());
        }
    }

    // Hent status til at filtrere sager på forsiden
    function getStatus()
    {
        global $dbcon;
        
        try {
                $table = "ticketStatus";

                $result = $dbcon->getAllData($table);

                return $result;
            } catch (Exception $e) {
                 throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
            }
    }

    function getTicketByStatus($fielddata)
    {
        global $dbcon;

        try
        {
            $table = "tickets";
            $rows = "tickets.*, ticketCategory.name AS category_name, ticketCategory.color AS category_color, ticketStatus.name AS status_name, ticketStatus.color AS status_color, ticketPriority.name AS priority_name, ticketPriority.color AS priority_color, users1.username AS assignedTo_name, users2.username AS createdBy_name";
            $join = "LEFT JOIN ticketCategory ON tickets.ticketCategory_id = ticketCategory.id 
                    LEFT JOIN ticketPriority ON tickets.ticketPriority_id = ticketPriority.id
                    LEFT JOIN ticketStatus ON tickets.ticketStatus_id = ticketStatus.id
                    LEFT JOIN users users1 ON tickets.assigned_to = users1.id
                    LEFT JOIN users users2 ON tickets.created_by = users2.id";
            $fieldname = "ticketStatus_id";
            $fielddata .= "' AND tickets.location_id = '{$_SESSION['location_id']}";
            $sortBy ="id";

            $result = $dbcon->getDataByFieldSortedWithJoins($table, $rows, $join, $fieldname, $fielddata, $sortBy);

            return $result;
        }
        catch (Exception $e)
        {
             throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
        }
    }

    // Hent prioriteringer
    function getPriority()
    {
        global $dbcon;
        
        try {
                $table = "ticketPriority";

                $result = $dbcon->getAllData($table);

                return $result;
            } catch (Exception $e) {
                 throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
            }
    }

      // Hent sagskategorier
    function getCategory()
    {
        global $dbcon;
        
        try {
                $table = "ticketCategory";

                $result = $dbcon->getAllData($table);

                return $result;
            } catch (Exception $e) {
                 throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
            }
    }

       // Hent sagskategorier
    function getUsers()
    {
        global $dbcon;
        
        try {
                $table = "users";

                $result = $dbcon->getAllData($table);

                return $result;
            } catch (Exception $e) {
                 throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
            }
    }


?>