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
    public $name;
    public $image;
    public $seasons = array();
    public $currentEpisode;
    public function __construct($id, $name, $image, $seasons, $currentEpisode){
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->seasons = $seasons;
        $this->currentEpisode = $currentEpisode;
    }
}

class Shows {
    static function create($show){
        $query = "INSERT INTO shows (name, image, seasons, currentEpisode) VALUES ($1, $2, $3, $4)";
        $query_params = array($show->name, $show->image, $show->seasons, $show->currentEpisode);
        pg_query_params($query, $query_params);
        return self::all();
    }
    static function update($updated_show){
        $query = "UPDATE shows SET name = $1, image=$2, seasons=$3, currentEpisode=$4 WHERE id=$5";
        $query_params = array($updated_show->name, $updated_show->image, $updated_show->seasons, $updated_show->currentEpisode, $updated_show->id);
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

        $results = pg_query("SELECT * FROM shows ORDER BY name ASC");

        $row_object = pg_fetch_object($results);
        while($row_object) {
            $new_show = new Show(
                intval($row_object->id),
                $row_object->name,
                $row_object->image,
                intval($row_object->seasons),
                intval($row_object->currentepisode)
            );
            $shows[] = $new_show;

            $row_object = pg_fetch_object($results);
        }
        return $shows;
    }
}
?>
