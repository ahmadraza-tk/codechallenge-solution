<?php 
/**
 * Author: Ahmad Raza
 * Description: Fetches Data from DB
 */

require_once('./core/connection.php');

class DB {

    /**
     * @var Connection
     */    
    private $connection = null;
    
    /**
     * @var DB
     */    
    private static $instance = null;

    private function __construct()
    {
        $this->connection = Connection::getInstance();  
    }

    /**
     * @return DB
     */    
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new DB();
        }

        return self::$instance;
    }

    /**
     * @param $searchTerm Search term 
     * @return string
     */     
    public function getQuery($searchTerm)
    {

        $query = 
        "
            SELECT
                session.name as session_name,
                user.name,
                session.session_start,
                session.session_end,
                CONCAT(session.name, ' - ', 
                    DATE_FORMAT(session.session_start, '%b %D %Y %h:%i %p'),
                    '-',
                    DATE_FORMAT(session.session_end, '%h:%i %p')                    
                ) 
                as session_info_formatted,
                session.id as session_id
            FROM SESSION

            INNER JOIN role 
                ON session.id = role.sessionid
            
            INNER JOIN USER
                ON role.userid = user.id
            WHERE 
                session.active <> 'N'
                AND role.usertype = 'speaker'
        ";

        if ($searchTerm) {
            $query .= "
                AND 
                (
                    session.name LIKE '%$searchTerm%'
                    OR user.name LIKE '%$searchTerm%'
                    OR session.session_start LIKE '%$searchTerm%'
                    OR session.session_end LIKE '%$searchTerm%'
                )
            ";
        }

        $query .= "
            ORDER BY LOWER(SUBSTRING_INDEX(user.name, ' ', -1)) DESC; 
        ";
        
        return $query;
        
    }

    /**
     * @param $searchTerm 
     * @return array of stdClass objects
     * @throws Exception
     */    
    public function fetchSessions($searchTerm = null) 
    {
        $conn = $this->connection->getConnectionHandle();

        $searchTerm = $conn->escape_string($searchTerm);

        $query = $this->getQuery($searchTerm);

        $sessions = $conn->query($query);
                
        if ($sessions) {
            $sessions = json_decode(json_encode($sessions->fetch_all(MYSQLI_ASSOC)));
            $formattedSession = $this->formatSessionsData($sessions);
            return $formattedSession;
        }else {
            throw new Exception('SQL Exception : ' . $conn->error);
        }      
    }

    /**
     * @param sessions Array
     * @return Array 
     */
    public function formatSessionsData($sessions) 
    {
        $sessionsFormatted = [];
        foreach($sessions as $session) {
            if (empty($sessionsFormatted[$session->session_id])) {
                $sessionsFormatted[$session->session_id] = $session;
                $sessionsFormatted[$session->session_id]->users = [
                    $session->name // name of user
                ];
            } else {
                array_push($sessionsFormatted[$session->session_id]->users, $session->name);// name of user
            }
        }

        return array_values($sessionsFormatted);
    }
}