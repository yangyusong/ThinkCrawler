<?php
/**
 * @date 2011-05-8
 * @author: YuSongYang
 * @mail yys159258@126.com
 * 包括常规的数据库操作
 * 1.查询
 * a.按照表查出所有
 * b.按照表和字段数组条件查出需要的这些字段
 * c.按照联合主键进行查询，（每个字段均为键值对）
 * d.获取所有id,这个id并非就叫id，满足如下条件：主键之一，数字类型，唯一性
 * 2.添加
 * 3.这些函数包括了 条件，排序，限数
 */
include_once __DIR__.'/class_database.php';
//include_once __DIR__.'/db_more.php';
/**
 *
 * @param <type> $tab
 * @param <type> $order 这个参数要经过order函数来得到再使用
 * @return <type>
 */
function query($tab, $orders = array())
{
    $order = order($orders);
    $db = new database();
    $sql = "SELECT * FROM `$tab`" . $order;
    return $db->qy($sql);
}

/**
 *按字段查询
 * @param <array> $fields 要得到的字段
 * @param <type> $tab 表
 * @return <type>
 */
function query_part($fields, $tab, $orders = array())
{
    $order = order($orders);
    $sel = "`" . implode("`,`", $fields) . "`";
    $db = new database();
    $sql = "SELECT distinct {$sel} FROM `{$tab}`" . $order;
    return $db->qy($sql);
}

/**
 * 必须满足字段的查询
 * @param <string> $tab
 * @param <kv> $eqs
 * @param <type> $order
 * @return <type>
 */
function query_eqs($tab, $eqs, $orders = array())
{
    return query_like1("*", $tab, array(), array(), $eqs, $orders);
}

/**
 * 满足字段范围查询
 * @param <type> $tab
 * @param <type> $ranges
 * @param <type> $orders
 * @return <type>
 */
function query_rg($tab, $ranges, $orders = array())
{
    return query_like1("*", $tab, $ranges, array(), array(), $orders);
}

/**
 * 搜索符合条件的记录
 * @param <string> $tab
 * @param <arr> $likes:数组，包括两个数组，字段数组和搜索字符数组
 * @param <arr> $orders
 */
function search_like($tab, $likes, $orders = array())
{
    return search_like2("*", $tab, array(), $likes, array(), $orders);
}

function search_like1($tab, $ranges, $likes, $eqs, $orders = array())
{
    return search_like2("*", $tab, $ranges, $likes, $eqs, $orders);
}

/**
 * 模糊搜索 SELECT * FROM `game_server` WHERE `caption` LIKE '%内%%服%' OR `name` LIKE '%内%%服%' LIMIT 0 , 30
 * @param <type> $fields
 * @param <type> $tab
 * @param <type> $ranges
 * @param <type> $likes
 * @param <type> $eqs
 * @param <type> $orders
 */
function search_like2($fields, $tab, $ranges, $likes, $eqs, $orders = array())
{
    $order = order($orders);
    $where = search_mult_where($ranges, $likes, $eqs);
    if (is_array($fields))
        $sel = "`" . implode("`,`", $fields) . "`";
    else
        $sel = $fields;
    $db = new database();
    $sql = "SELECT {$sel} FROM `{$tab}` {$where} " . $order;
//    echo $sql;
    return $db->qy($sql);
}

/**
 * 模糊查询
 * @param <type> $tab
 * @param <type> $likes
 * @param <type> $order
 * @return <type>
 */
function query_like($tab, $likes, $orders = array())
{
    return query_like1("*", $tab, array(), $likes, array(), $orders);
}

/**
 * 模糊查询 SELECT * FROM `sys_mon` group by `x` , `y` order by `x` , `y` desc
 * @param <array or string> $fields：要查询的字段，字符串时为单个字段或*
 * @param <type> $tab：要查询的表
 * @param <kv> $ranges：要查询范围的字段
 * @param <kv> $likes：要模糊查询的字段
 * @param <kv> $eqs：固定值的字段
 * @param <kv> $order：排序指令 k:排序字段 ,v:排序方式
 * @return <rs>
 */
function query_like1($fields, $tab, $ranges, $likes, $eqs, $orders = array())
{
    $order = order($orders);
    $where = mult_where($ranges, $likes, $eqs);
    if (is_array($fields))
        $sel = "`" . implode("`,`", $fields) . "`";
    else
        $sel = $fields;
    $db = new database();
    $sql = "SELECT {$sel} FROM `{$tab}` {$where} " . $order;
//    echo $sql;
    return $db->qy($sql);
}

/**
 * 主键作为条件来查询
 * @param <string> $tab
 * @param <array> $fields array($key=>$value,)
 */
function query_keys($tab, $fields)
{
    $where = "where 1=1 ";
    foreach ($fields as $key => $value) {
        $where .= " and `$key` = '$value'";
    }
    $db = new database();
    $sql = "SELECT * FROM `{$tab}` {$where}";
//    echo $sql;
    return $db->qy($sql);
}

/**
 * 获取所有id (名称不一定就叫id)
 * @param <string> $id
 * @param <string> $tab
 * @return
 */
function query_ids($tab, $id, $orders = array())
{
    $order = order($orders);
    $db = new database();
    $sql = "SELECT distinct `$id` FROM `{$tab}`" . $order;
//    echo $sql;
    return $db->qy($sql);
}

/**
 * 添加一条记录
 * @param <type> $tab 表名
 * @param <type> $fs_name 字段数组
 * @param <type> $fs_value 值数组
 */
function add($tab, $fs_name, $fs_value)
{
    $db = new database();
    $fs_name = "`" . implode("`,`", $fs_name) . "`";
    $fs_value = "'" . implode("','", $fs_value) . "'";
    $sql = "insert into `{$tab}`({$fs_name}) values({$fs_value})";
//    echo $sql;
    $result = $db->execute($sql);
    $db = NULL;
    return $result;
}

/**
 * 更新一条记录
 * @param <type> $tab 表名
 * @param <type> $kv 键值对 键为字段，值为值，
 * @param <type> $where 条件，键值对 键为字段，值为值
 * @return <type>
 */
function update($tab, $kv, $where)
{
    $db = new database();
    $new_val = array();
    foreach ($kv as $key => $value) {
        if (!is_int($key))
            $new_val[] = "`" . $key . "`='" . $value . "'";
    }
    $new_val = implode(",", $new_val);

    $where = where($where);

    $sql = "update `{$tab}` set
        {$new_val}
        where {$where}";
//        echo $sql;
    $affect = $db->execute($sql);
    return $affect;
}

/**
 *
 * @param <type> $tab
 * @param <type> $fs_name
 * @param <type> $fs_value
 */
function add_only($tab, $fs_name, $fs_value)
{
    $keys = get_keys($tab);
    $kv = k_v_to_kv($fs_name, $fs_value);
    $keys = kv_part($keys, $kv);
    $rs = query_eqs($tab, $keys);
    //    print_r($rs);
    if (empty ($rs)) {
        //        echo "add";
        add($tab, $fs_name, $fs_value);
        return true;
    } else {
        return false;
    }
}

/**
 *包括了按键删除
 * @param <type> $tab  表名
 * @param <type> $where  条件，键值对 键为字段，值为值
 * @return <type>
 */
function delete_key($tab, $where)
{
    $where = where($where);
    $db = new database();
    $sql = "delete from `{$tab}`  where {$where}";
    return $db->execute($sql);
}

/**
 * 清空tab表
 * @param <type> $tab
 * @return <type>
 */
function truncate($tab)
{
    $db = new database();
    $sql = "truncate table `{$tab}`";
    return $db->execute($sql);
}

/*
 * internal api
 */

/**
 * 条件语句输出
 * @param <array> $where 条件，键值对 键为字段，值为值
 * @return <string> 条件字符串
 */
function where($where)
{
    $new_where = array();
    foreach ($where as $key => $value) {
        $new_where[] = "`" . $key . "`='" . $value . "'";
    }
    return implode(" and ", $new_where);
}

/**
 *
 * @param <type> $ranges 按范围查找的数组，array(field=>array(min,max))  min,max<-number | string
 * @param <type> $likes 模糊查询的字段数组，array(field=>value,)
 * @param <type> $eqs 精确查询的字段，array(field,)
 */
function mult_where($ranges, $likes, $eqs)
{
    $where = "where 1=1 ";
    foreach ($ranges as $field => $range) {
        $where .= " and `$field`>='{$range[0]}' and `$field`<='{$range[1]}'";
    }
    foreach ($likes as $field => $value) {
        $where .= " and `$field` like '%$value%'";
    }
    foreach ($eqs as $field => $value) {
        $where .= " and `$field` = '$value'";
    }
    return $where;
}

function search_mult_where($ranges, $likes, $eqs)
{
    $where = "where 1=1 ";
    $min = 0;
    $max = 99999999999;
    foreach ($ranges as $field => $range) {
        if ($range[0] == "" && $range[1] != 0)
            $range[0] = $min;
        if ($range[0] != "" && $range[1] == 0)
            $range[1] = $max;
        if ($range[0] != "" && $range[1] != 0)
            $where .= " and `$field`>='{$range[0]}' and `$field`<='{$range[1]}'";
    }

    if (!empty ($likes)) {
        $fields = $likes[0];
        $character = $likes[1];
        $v = " '%" . implode("%', '%", $character) . "%' ";
        $l = array();
        if (!empty ($character))
            if (!(count($character) == 1 && $character[0] == ""))
                foreach ($fields as $field) {
                    $l[] = "`$field` like $v";
                }
        if (!empty ($l))
            $where .= " and " . implode(" or ", $l);
    }

    foreach ($eqs as $field => $value) {
        if ($value != "")
            $where .= " and `$field` = '$value'";
    }
    return $where;
}

/**
 * 排序语句
 * $value:""或"desc"
 * @param <kv> $orders
 */
function order($orders = array())
{

    $group_arr = array_keys($orders);
    $group_num = count($group_arr);
    $groupby = "";
    if ($group_num > 1) {
        $groupby = " group by ";
        array_pop($group_arr);
        $groupby .= implode(",", $group_arr);
    }

    $arr = array();
    foreach ($orders as $key => $value) {
        $arr[] = $key . " " . $value;
    }
    $orderby = "";
    if (!empty ($orders))
        $orderby = " order by ";
    $orderby .= implode(",", $arr);

    return $groupby . $orderby;
}
