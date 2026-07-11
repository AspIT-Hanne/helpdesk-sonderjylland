<?php
    function getEnvVar($key) {
    // __DIR__ refererer til mappen, hvor connect.php ligger.
    $envPath = realpath(__DIR__ . '/..') . '/.env'; 

    if (!file_exists($envPath)) {
       return null;
    }  
    
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // Læser .env filen linje for linje og ignorerer tomme linjer og linjeskift
        // file(): åbner en .env-fil og omdanner den til et array, hvor hver linje er et element.
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, '=') !== false && strpos($line, $key) === 0) {
            // Tjekker om linjen indeholder et lighedstegn, og om den starter med vores søge-nøgle
            // strpos($line, $key) === 0: sikrer, at den kun kigger på linjer, der starter præcis med din nøgle (f.eks. DB_HOST=...).
                return trim(substr($line, strpos($line, '=') + 1));
                // Hvis et match findes, returneres alt indhold efter lighedstegnet som værdien
                // substr(): Når linjen er fundet, "skærer" den alt teksten før lighedstegnet væk, så du kun får selve værdien tilbage (f.eks. localhost i stedet for DB_HOST=localhost).
            }
        }

        return null;
        // Returnerer null, hvis nøglen ikke blev fundet i filen
    }

    // Klasse til DB forbindelse og CRUD funktionalitet
    
    class DbOperations
    {
        public $connection;
        
       // Opret forbindelse til databasen. For at oprette et nyt DbOperations objekt skal der først etableres forbindelse til databasen
        public function __construct()
        {
            $host = getEnvVar('DB_HOST');
            $db   = getEnvVar('DB_NAME');
            $user = getEnvVar('DB_USER');
            $pass = getEnvVar('DB_PASS');

            // $dsn er en forkortelse for Data Source Name, som bruges af PHP Data Objects (PDO) til at specificere forbindelsesparametre til databasen.
            // $dsn består af flere dele:
            // 1. mysql: Angiver, at vi bruger MySQL-database som database-driver.
            // 2. host: Angiver værtsnavnet (DB_HOST).
            // 3. dbname: Angiver databasenavnet (DB_NAME).
            // 4. charset: Angiver tegnsættet (DB_CHARSET).
            // I eksemplet ovenfor vil den færdige DSN-streng se sådan ud:
            // mysql:host=localhost;dbname=helpdesk_sonderjylland;charset=utf8mb4

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            // Forsøg at oprette forbindelse til databasen ved at bruge try-catch blokken
            // for at håndtere eventuelle fejl under forbindelsesprocessen.
            // Fordelen ved at anvende try-catch er, at vi kan fange evt. problemer, som opstår (Exceptions)
            // og håndtere dem på en kontrolleret måde, hvilket forbedrer applikationens stabilitet og brugervenlighed.

            // PDO-indstillinger for at forbedre sikkerhed og ydeevne
            
            $options = [
                // Cache forbindelsen til databasen (persistent connection)
                // På den måde genbruges den samme databaseforbindelse i stedet for at oprette en ny for hver forespørgsel.
                // Dette kan forbedre ydeevnen betydeligt under belastning.
                PDO::ATTR_PERSISTENT         => true,
                
                // Indstil standardfejltilstanden til at kaste undtagelser (Exceptions)
                // Dette er den vigtigste best practice for fejlsikring!
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                
                // Deaktiver emulerede forberedte statements.
                // Brug af ægte forberedte statements forhindrer SQL Injection og forbedrer ydeevnen.
                // Eksempel på SQL Injection uden forberedte statements:
                // SELECT * FROM users WHERE username = '$_POST['username']'
                // $_POST['username'] = "' OR '1'='1"
                // SELECT * FROM users WHERE username = '' OR '1'='1' -- Dette vil returnere den første bruger, som sandsynligvis er admin-brugeren.
                PDO::ATTR_EMULATE_PREPARES   => false,
                
                // Sæt standard fetch mode til at returnere et associative array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                // Opretter en ny PDO-instans (forbindelsen)
                // @new bruges for at undertrykke default fejlmeddelelser
                $this->connection = @new PDO($dsn, $user, $pass, $options);
                
            } catch (\PDOException $e) {
                // Fanger kun PDO Exceptions
                
                // Ved produktionsmiljøer skal du undgå at vise $e->getMessage() til brugeren, 
                // da det kan afsløre følsomme databaseoplysninger.
                // Log i stedet fejlen: error_log($e->getMessage());
                
                // Da dette er et lokalt test-miljø udskriver vi evt. fejlmeddelelser, så vi nemt kan se, hvad problemet er
                // Samtidigt afsluttes scriptet med exit() for at forhindre yderligere eksekvering.
                exit("Databaseforbindelsen mislykkedes: " . $e->getMessage()); 
            }

            return $this->connection;
        }

        // Metode til at hente alle data fra en tabel
        public function getAllData($table)
        {
            return $this->getData($table);
        }

        // Metode til at hente en post fra en tabel baseret på post ID
        public function getDataByID($table, $ID)
        {
            if ($this->tableExists($table))
            {
                // Opret WHERE-statement ved at hente tabellens ID-felt og lave det om til en streng sammen med det overførte post-ID fx (WHERE) PID = '13'
                $sqlWhere = $this->getTableID($table) . " = '" . $ID . "'";

                // Kald den private metode getData for at hente data med det overførte ID
                $dataResult = $this->getData($table, "*", null, $sqlWhere);

                // getData-metoden returnerer et multidimensionelt associative array, men skal kun returnere et resultat, så den returnerer en dimension af arrayet ved kun at returnere det første resultat
                return $dataResult[0];
            }
        }

         // Metode til at hente en post fra en tabel baseret på et bestemt felt og værdien i feltet
         public function getDataByField($table, $fieldname, $fielddata)
         {
             if ($this->tableExists($table))
             {
                 // Opret WHERE-statement med det overførte felt og den overførte værdi
                 $sqlWhere = "$fieldname = '$fielddata'";
 
                 // Kald den private metode getData for at hente data med de overførte værdier i det overførte felt
                 $dataResult = $this->getData($table, "*", null, $sqlWhere);
 
                 // Se forklaring i linje 58
                 return $dataResult[0];
             }
         }

        public function getSortedData($table, $sortBy)
        {
            if ($this->tableExists($table))
            {
                // Kald den private metode getData for at hente data fra en tabel med "ORDER BY" som sorterer dataene efter det overførte felt
                $dataResult = $this->getData($table, "*", null, null, $sortBy);

                return $dataResult;
            }
        }

         // Metode til at hente alle posts fra en tabel som opfylder en bestemt værdi i det overførte felt og sorteret efter et bestemt felt. Anvendes til at hente alle events fra en bestemt uge sorteret efter starttidspunkt
         public function getDataByFieldSorted($table, $fieldname, $fielddata, $sortBy)
         {
             if ($this->tableExists($table))
             {
                 // Opret WHERE-statement med det overførte felt og den overførte værdi
                 $sqlWhere = "$fieldname = '$fielddata'";
 
                 // Kald den private metode getData for at hente data med de overførte værdier i det overførte felt og sorteret efter det ønskede felt
                 $dataResult = $this->getData($table, "*", null, $sqlWhere, $sortBy);

                 return $dataResult;
             }
         }

         // Metode til at hente data med inner joins
         public function getDataWithJoins($table, $rows, $join)
         {
            if ($this->tableExists($table))
             {
                // Kald den private metode getData for at hente de ønskede data fra det overførte tabelnavn og joins
                $dataResult = $this->getData($table, $rows, $join);

                return $dataResult;
             }
         }

        // Metode til at oprette ny post/indsætte data i tabel. $data=array() argumentet opretter et tomt array, som kan bruges som default hvis metoden ikke får et array af data overført
        public function insertData($table, $data=array())
        {
            // Kontroller om tabellen eksisterer i databasen
            if ($this->tableExists($table))
            {
                // Definer dine felter
                $fields = array_keys($data);
                $columnString = implode(", ", $fields);
    
                // Lav placeholders (f.eks. :navn, :email)
                // Vi mapper array-nøgler til :key format
                $placeholders = ":" . implode(", :", $fields);

                // Byg SQL
                $sql = "INSERT INTO {$table} ({$columnString}) VALUES ({$placeholders})";

                try {
                    $stmt = $this->connection->prepare($sql);
                    
                    // Udfør ved at sende $data direkte ind.
                    // PDO sørger nu for at "escape" alt indhold sikkert.
                    return $stmt->execute($data);
                    
                } catch (PDOException $e) {
                    error_log("Database fejl: " . $e->getMessage());
                    return false;
                }
            }    
            else
            {
                echo("Tabel eksisterer ikke");
                return false;
            }
        }

        // Metode til at opdatere en post, der allerede eksisterer i tabellen (see linje 105 for forklaring af $data=array())
        public function updateData($table, $ID, $data=array())
        {
            // Kontroller om tabellen eksisterer i databasen
            if($this->tableExists($table))
            {
                // Opret array $newValues til at overføre de nye data til
                $newValues = array();

                // Opret WHERE statement (se linje 52 for forklaring)
                $sqlWhere = $this->getTableID($table) . " = '" . $ID . "'";
    
                // Gennemløb $_POST data for at opsætte dem i det rigtige format - fx. name = "Rose" og overfør dem til $newValues med hvert datasæt af index og værdi på hver sit index i arrayet - fx. $newValues[name] = "Rose"
                foreach($data as $key=>$value)
                {
                    // Se forklaring i linje 113
                    $trimmedValue = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);
                    $newValues[] = $key . ' = "' .$trimmedValue . '"';
                }

                // Omdan array til string og adskil hvert index/værdi par med komma (,) - fx. name = "Rose", type = "Flower" etc.
                $newValueString = implode(',' ,$newValues);

                // Opret UPDATE statement med værdierne fra $newValueString og WHERE kriteriet
                $sqlUpdate = "UPDATE {$table} SET {$newValueString} WHERE {$sqlWhere}";
                
                // Kør SQL-query. Hvis SQL-query lykkes returner TRUE ellers udskriv fejl og returner FALSE
                if($this->connection->query($sqlUpdate))
                {
                    return true;
                }
                else
                {
                    echo $this->connection->error;
                    return false;
                }
            }
            else
            {
                // Hvis tabellen ikke eksisterer
                return false;
            }
        }

        // Metode til at slette en post fra tabellen
        public function deleteData($table, $ID)
        {
            // Kontroller om tabellen eksisterer i databasen
            if($this->tableExists($table))
            {
                // Opret WHERE statement (se linje 52 for forklaring)
                $sqlWhere = $this->getTableID($table) . " = '" . $ID . "'";                

                // Opret DELETE statement
                $sqlDelete = "DELETE FROM {$table} WHERE {$sqlWhere}";
                
                // Kør SQL-query. Hvis SQL-query lykkes returner TRUE ellers udskriv fejl og returner FALSE
                if($this->connection->query($sqlDelete))
                {
                    return true;
                }
                else
                {
                    echo $this->connection->error;
                    return false;
                }
            }
            else
            {
                // Hvis tabellen ikke eksisterer
                return false;
            }
        }

        // Metode til at slette alle data i en tabel
        public function emptyTable($table)
        {
            // Kontroller om tabellen eksisterer i databasen
            if($this->tableExists($table))
            {
                // Opret TRUNCATE TABLE statement
                $sqlEmptyTable = "TRUNCATE TABLE {$table}";
                
                // Kør SQL-query. Hvis SQL-query lykkes returner TRUE ellers udskriv fejl og returner FALSE
                if($this->connection->query($sqlEmptyTable))
                {
                    return true;
                }
                else
                {
                    echo $this->connection->error;
                    return false;
                }
            }
            else
            {
                // Hvis tabellen ikke eksisterer
                return false;
            }
        }

        // Metode til at hente det nyeste/højeste ID i en tabel. Anvendes til at vise den nyeste post i en tabel, når man opretter nye posts
        public function getNewestID($table)
        {
            // Kontroller om tabellen eksisterer i databasen
            if ($this->tableExists($table))
            {
                // Hent navnet på feltet, som indeholder ID'er i denne tabel
                $tableID = $this->getTableID($table);

                // Hent data og sorter dem oppefra og ned efter ID
                $allData = $this->getData($table, "*", null, null, "{$tableID} DESC");

                // Fordi data hentes i omvendt rækkefølge ligger den nyeste post (med det højeste ID) på plads 0 i resultat-arrayet og navnet på ID-feltet i den aktuelle tabel blev hentet tidligere og lagt i variablen $tableID
                return $allData[0][$tableID];
            }
        }

        // Generisk privat funktion til at hente data fra tabel. Kan kun kaldes fra klassen og ikke fra andre php-sider
        private function getData($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
        {
            $selectQuery = "";
            // Kontroller om tabellen eksisterer i databasen
            if ($this->tableExists($table))
            {
                // Opret grundlæggende SQL SELECT statement
                $selectQuery = "SELECT {$rows} FROM {$table}";

                // Hvis JOIN er defineret, tilføj den til SQL statement
                if ($join != null)
                {
                    $selectQuery .= " {$join}";
                }

                // Hvis WHERE er defineret, tilføj den til SQL statement
                if ($where != null)
                {
                    $selectQuery .= " WHERE {$where}";
                }

                // Hvis ORDER BY er defineret, tilføj den til SQL statement
                if ($order != null)
                {
                    $selectQuery .= " ORDER BY {$order}";
                }

                // Hvis LIMIT er defineret, tilføj den til SQL statement
                if ($limit != null)
                {
                    $selectQuery .= " LIMIT {$limit}";
                }

                $stmt = $this->connection->prepare($selectQuery);

                try
                {
                    $stmt->execute();

                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch (PDOException $e) {
                    // Her fanger vi fejl fra både execute() OG fetchAll()
                    error_log("Database fejl: " . $e->getMessage());
                    return ["No data was found"];
                }

                
            }
            else
            {
                // Stop script og udskriv fejlmeddelelse
                die("Table does not exist in database");
            }
            
        }

        // Hent navn på tabellens ID-felt
        private function getTableID($table)
        {
            // Hent alle felter/kolonner fra tabel for at kunne finde navnet på ID-feltet (altid første felt i en tabel)
            $result = $this->connection->query("SHOW COLUMNS FROM {$table}");

            // Hent den første række fra de hentede felter og læg det i et associative array. Dette array kommer til at indeholde alle informationer om det første felt: navn, datatype, autotæller osv. Evt. print_r arrayet og sammenlign med tabelvisning fra phpmyadmin
            $dbFields = $result->fetch_row();

            // Returner det første index i arrayet med felt-informationer. Første index indeholder altid feltets navn, som så kan bruges til at oprette SQL WHERE statement: (WHERE) ID FIELDNAME = $ID (hvor navnet på denne tabels ID-felt skal bruges til at finde en bestemt post)
            return $dbFields[0];
        }

        // Returner true hvis tabellen findes i databasen og false hvis den ikke gør
        private function tableExists($table)
        {
            // Hent alle tabelnavne fra databasen
            $result = $this->connection->query("SHOW TABLES");

            // Læg de hentede tabelnavne i et multidimensionelt associative array med tabelnavne på index 0
            $dbTables = $result->fetchAll();

            // Hvis tabelnavnet findes i kolonne 0 (tabelnavne) af alle tabelinformationer
            if(in_array($table, array_column($dbTables, "Tables_in_helpdesk_sonderjylland")))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
?>

