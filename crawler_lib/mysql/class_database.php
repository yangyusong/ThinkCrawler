<?php
/* 
 * @author: YuSongYang
 * @date: 2010-12-30
 */

require_once ("sys_conf.inc");
class database
{
    private $host;
    private $user;
    private $pwd;
    private $name;
    private $connection;

    function __get($property_name)
    {
        if (isset($this->$property_name)) {
            return ($this->$property_name);
        } else {
            return (null);
        }
    }

    function __set($preperty_name, $value)
    {
        $this->$property_name = $value;
    }

    function __construct()
    {
        $this->host = sys_conf::$DBHOST;
        $this->user = sys_conf::$DBUSER;
        $this->pwd = sys_conf::$DBPASSWORD;
        $this->name = sys_conf::$DBNAME;
        $this->connection = mysql_connect($this->host, $this->user, $this->pwd) or die("链接不了数据库");
        mysql_select_db($this->name, $this->connection);
    }

    function __destruct()
    {
        mysql_close($this->connection);
    }

    /**
     *
     * @param <string> $sql
     * @return <bool> result
     */
    function execute($sql)
    {
        mysql_query('set names utf8');
        mysql_query('START TRANSACTION') or exit(mysql_error());
        mysql_query($sql);
        mysql_query('COMMIT');
        $affect = mysql_affected_rows();
        if (mysql_error() != "") {
            mysql_query('ROLLBACK') or exit(mysql_error());
            $affect = -1;
//			echo "<span class='stylered'>执行失败</span>";
        } else {
//            echo "<span class='styleblue'>执行成功</span>";
        }
        //API返回值，0表示成功，>0表示有警告之类，<0，则表示严重错误
        return $affect;
    }

    /**
     *
     * @param <string> $table
     * @return <array> rs
     */
    function fields($table)
    {
        $data = array();
        //        $sql = "show fields from `$table`";//SQL得到全表信息
        $sql = "select * from `$table`";

        $query = mysql_query($sql);
        while ($row = mysql_fetch_field($query)) {
            $data[] = $row;
//            echo "字段名：".$row->name."<br />";
//            echo "字段类型：".$row->type."<br />";
//            echo "是否主键：".$row->primary_key."<br />";
//            echo "最大长度：".$row->max_length."<br />";
        }
        return $data;

    }

    function keys($table)
    {
        $data = array();
        $sql = "select * from `$table`";

        $query = mysql_query($sql);
        while ($row = mysql_fetch_field($query)) {
            if ($row->primary_key)
                $data[] = $row;
        }
        return $data;
    }

    /**
     *
     * @param <string> $sql
     * @return <array> rs
     */
    function query($sql)
    {
        //echo $sql
        $result_array = array();
        mysql_query('set names utf8');
        mysql_query('START TRANSACTION') or exit(mysql_error());
        $query_result = mysql_query($sql, $this->connection);
        if ($query_result != null && count($query_result) != 0)
            while ($row = mysql_fetch_object($query_result)) {
                $result_array[] = $row;
            }

        mysql_query('COMMIT') or exit(mysql_error());
        if (mysql_error() != "") {
            mysql_query('ROLLBACK') or exit(mysql_error());
            echo "<span class='stylered'>执行失败</span><br/>";
        } else {
//            echo "<span class='styleblue'>执行成功</span><br/>";
        }
        return $result_array;
    }

    function mysql_q($sql)
    {
        mysql_query('set names utf8');
        return mysql_query($sql, $this->connection);

    }

    function qy($sql)
    {
        $result_array = array();
        mysql_query('set names utf8');
        mysql_query('START TRANSACTION') or exit(mysql_error());
        $query_result = mysql_query($sql, $this->connection);
        if ($query_result != null && count($query_result) != 0)
            while ($row = mysql_fetch_array($query_result)) {
                $result_array[] = $row;
            }

        mysql_query('COMMIT') or exit(mysql_error());
        if (mysql_error() != "") {
            mysql_query('ROLLBACK') or exit(mysql_error());
            echo "<span class='stylered'>执行失败</span><br/>";
        } else {
//            echo "<span class='styleblue'>执行成功</span><br/>";
        }
        return $result_array;
    }
}

?>