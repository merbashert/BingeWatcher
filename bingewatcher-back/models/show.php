<?php
// $dbconn = pg_connect('host=localhost dbname=binge');

$dbconn = null;
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
    $dbconn = pg_connect("host=localhost dbname=binge");
}

class Show {
    public $id;
    public $omdb_id;
    public $name;
    public $image;
    public $seasons = array();
    public $current_episode;
    public function __construct($id, $omdb_id, $name, $image, $seasons, $current_episode){
        $this->id = $id;
        $this->omdb_id = $omdb_id;
        $this->name = $name;
        $this->image = $image;
        $this->seasons = $seasons;
        $this->current_episode = $current_episode;
    }
}

class Shows {
    static function create($show){
        $query = "INSERT INTO shows (omdb_id, name, image, seasons, current_episode) VALUES ($1, $2, $3, $4, $5)";
        $query_params = array($show->omdb_id, $show->name, $show->image, $show->seasons,$show->current_episode);
        pg_query_params($query, $query_params);
        return self::all();
    }
    static function update($updated_show){
        $query = "UPDATE shows SET omdb_id = $1, name=$2, image=$3, seasons=$4, current_episode=$5 WHERE id=$6";
        $query_params = array($updated_show->omdb_id, $updated_show->name, $updated_show->image, $updated_show->seasons, $updated_show->current_episode, $updated_show->id);
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

        $results = pg_query("SELECT * FROM shows");

        $row_object = pg_fetch_object($results);
        while($row_object) {
        $new_show = new Show(
            intval($row_object->id),
            intval($row_object->omdb_id),
            $row_object->name,
            $row_object->image,
            $row_object->seasons,
            intval($row_object->current_episode)
        );
        $shows[] = $new_show;

        $row_object = pg_fetch_object($results);
        }
        return $shows;
        }
        }
        ?>
