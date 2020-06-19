    <?php
$dbconn = pg_connect('host=localhost dbname=binge');

// $dbconn = null;
// if(getenv('DATABASE_URL')){
//     $connectionConfig = parse_url(getenv('DATABASE_URL'));
//     $host = $connectionConfig['host'];
//     $user = $connectionConfig['user'];
//     $password = $connectionConfig['pass'];
//     $port = $connectionConfig['port'];
//     $dbname = trim($connectionConfig['path'],'/');
//     $dbconn = pg_connect(
//         "host=".$host." ".
//         "user=".$user." ".
//         "password=".$password." ".
//         "port=".$port." ".
//         "dbname=".$dbname
//     );
// } else {
//     $dbconn = pg_connect("host=localhost dbname=binge");
// }

class Show {
    public $id;
    public $title;
    public $service;
    public $numOfEpisodes;
    public $currentEpisode;
    public function __construct($id, $title, $service, $numOfEpisodes, $currentEpisode){
        $this->id = $id;
        $this->title = $title;
        $this->service = $service;
        $this->numOfEpisodes = $numOfEpisodes;
        $this->currentEpisode = $currentEpisode;
    }
}

class Shows {
    static function create($show){
        $query = "INSERT INTO shows (title, service, numOfEpisodes, currentEpisode) VALUES ($1, $2, $3, $4)";
        $query_params = array($show->title, $show->service, $show->numOfEpisodes, $show->currentEpisode);
        pg_query_params($query, $query_params);
        return self::all();
    }
    static function update($updated_show){
        $query = "UPDATE shows SET title = $1, service=$2, numOfEpisodes=$3, currentEpisode=$4 WHERE id=$5";
        $query_params = array($updated_show->title, $updated_show->service, $updated_show->numOfEpisodes, $updated_show->currentEpisode, $updated_show->id);
        pg_query_params($query,$query_params);

        return self::all();
    }
    static function delete($id){
        $query = "DELETE FROM shows WHERE id = $1";
        $query_params = array($id);
        pg_query_params($query, $query_params);

        return self::all();
    }
    static function all(){
        $shows = array();

        $results = pg_query("SELECT * FROM shows ORDER BY title ASC");

        $row_object = pg_fetch_object($results);
        while($row_object) {
            $new_show = new Show(
                intval($row_object->id),
                $row_object->title,
                $row_object->service,
                intval($row_object->numofepisodes),
                intval($row_object->currentepisode)
            );
            $shows[] = $new_show;

            $row_object = pg_fetch_object($results);
        }
        return $shows;
    }
}
?>
