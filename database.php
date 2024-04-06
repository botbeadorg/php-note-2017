<?php
/*
 * PHP之成为一种伟大的WEB编程语言的重要原因，就是它对数据库的广泛支持。
 * PHP 5生来便能够支持SQLITE，一种无需服务器的数据库技术。
 * PHP不但能与SQL数据库协同工作，它也支持NO-SQL数据库。
 */

class DBCnt_sqlite {
    // DSN：描述数据库的位置及名字，即database name，这个目录：ds's of loc。
    // 一个SQLITE数据库只是一个文件。
    /*
     * 把SQLITE数据库文件放在远离网站根目录的某个地方是一个好主意。
     * 在PHP中，sqlite扩展提供常规的SQLite访问，也包括SQLite版本2的PDO驱动；
     * pdo_sqlite扩展则提供针对SQLite版本3的PDO驱动。
     *
     * sqlite_master是一个特殊的系统表，它保存着其它表的相关信息，
     * 所以在检测一个特殊的表是否存在的时候就可以利用sqlite_master。
     */
    private static $dsn_prefix = 'sqlite:';
    // $sqlite = new PDO('sqlite::memory:');，创建内存中的临时数据库。
    // 保持连接的内部变量
    private static $db;
    // 不允许复制以及实例化
    final private function __construct() { }
    final private function __clone() { }
    public static function get($sql_db_file) {
        // 如果未曾连接则执行连接
        if (is_null(self::$db)) {
            $dsn = self::$dsn_prefix.$sql_db_file;
            echo $dsn;
            self::$db = new PDO($dsn);
        }
        // 最后返回这个连接
        return self::$db;
    }
}
function create_sqlite_table($table_name, $db_cn)
{
    // sqlite事务能够提高SQLITE查询的速度。
    $db_cn->beginTransaction();
    $q = $db_cn->query("SELECT name FROM sqlite_master WHERE type = 'table'" . " AND name = '$table_name'");
    if ($q->fetch() === false) {
        $db_cn->exec(<<<_SQL_
CREATE TABLE $table_name (
id INT UNSIGNED NOT NULL,
sign CHAR(11),
symbol CHAR(13),
planet CHAR(7),
element CHAR(5),
start_month TINYINT,
start_day TINYINT,
end_month TINYINT,
end_day TINYINT,
PRIMARY KEY(id)
)
_SQL_
);
        $db_cn->commit();
    }else{
        $db_cn->rollback();
    }
}

create_sqlite_table('fanran',DBCnt_sqlite::get('../col-fo-s,sd/sqliteOne.db'));

function insert_some_records($table_name, $db_cn)
{
    $sql_cmd=<<<_SQL_
INSERT INTO $table_name VALUES (1,'Aries','Ram','Mars','fire',3,21,4,19);
INSERT INTO $table_name VALUES (2,'Taurus','Bull','Venus','earth',4,20,5,20);
INSERT INTO $table_name VALUES (3,'Gemini','Twins','Mercury','air',5,21,6,21);
INSERT INTO $table_name VALUES (4,'Cancer','Crab','Moon','water',6,22,7,22);
INSERT INTO $table_name VALUES (5,'Leo','Lion','Sun','fire',7,23,8,22);
INSERT INTO $table_name VALUES (6,'Virgo','Virgin','Mercury','earth',8,23,9,22);
INSERT INTO $table_name VALUES (7,'Libra','Scales','Venus','air',9,23,10,23);
INSERT INTO $table_name VALUES (8,'Scorpio','Scorpion','Mars','water',10,24,11,21);
INSERT INTO $table_name VALUES (9,'Sagittarius','Archer','Jupiter','fire',11,22,12,21);
INSERT INTO $table_name VALUES (10,'Capricorn','Goat','Saturn','earth',12,22,1,19);
INSERT INTO $table_name VALUES (11,'Aquarius','Water Carrier','Uranus','air',1,20,2,18);
INSERT INTO $table_name VALUES (12,'Pisces','Fishes','Neptune','water',2,19,3,20);
_SQL_;
    $db_cn->beginTransaction();
    try{
        foreach (explode("\n",trim($sql_cmd)) as $q) {
            $db_cn->exec(trim($q));
        }
        $db_cn->commit();
    }catch (Exception $e){
        $db_cn->rollback();
    }
}
insert_some_records('fanran',DBCnt_sqlite::get('../col-fo-s,sd/sqliteOne.db'));

echo '<pre>';
// 使用PDO::getAvailableDrivers()来检测当前PHP的安装所支持的PDO后端。
print_r(PDO::getAvailableDrivers());
// 使用phpMyAdmin的时候，需要把phpMyAdmin的整个文件夹放在网站public文件夹中。

class DBCnt_mysql {
    private static $host_ip_prefix = 'mysql:host=';
    private static $port_prefix=';port=';
    private static $db_name_prefix=';dbname=';
    // 保持连接的内部变量
    private static $dbcn;
    // 不允许复制以及实例化
    final private function __construct() { }
    final private function __clone() { }
    public static function get($host_ip, $user, $password, $db_name=null, $port=null) {
        // 如果未曾连接则执行连接
        if (is_null(self::$dbcn)) {
            $dns = self::$host_ip_prefix.$host_ip;
            if(!empty($port)){
                $dns.=self::$port_prefix.$port;
            }
            if(!empty($db_name)){
                $dns.=self::$db_name_prefix.$db_name;
            }
            echo $dns;
            self::$dbcn = new PDO($dns, $user, $password);
        }
        // 最后返回这个连接
        return self::$dbcn;
    }
}
DBCnt_mysql::get('localhost','root','why1983316','mysql','3306');

function fetch_all_from_query()
{
    $db = DBCnt_sqlite::get('../col-fo-s,sd/sqliteOne.db');
    $st = $db->query('SELECT planet, element FROM fanran');
    // 使用fetchAll()获取一次查询的所有结果。
    // fetchAll()默认将每一行表达为一个既有数值索引又有关联索引的数组。
    $results = $st->fetchAll(PDO::FETCH_COLUMN,0);
    // 如果只希望获取结果中的某一列，那么fetchAll的第一个参数为PDO::FETCH_COLUMN，
    // 第二个参数是结果中列的索引，从0开始。
    echo '<br />';
    print_r($results);
    $st = $db->query('SELECT planet, element FROM fanran');
    // 在第一次fetchAll后，需要再次查询才能获得结果。
    $results1 = $st->fetchAll();
    print_r($results1);
}

fetch_all_from_query();

/*
 * PDO::query()用以向数据库发送SQL查询语句，然后可以使用foreach来提取查询结果中的每一行。
 * query()返回一个PDOStatement对象，它的fetchAll()方法提供了一种简洁的方式来操作从查询中返回的每一行。
 */
echo 'USE QUERY AND FETCHALL----------------'.'<br />';
$db_cn = DBCnt_sqlite::get('../col-fo-s,sd/sqliteOne.db');
$r_s = $db_cn->query('SELECT symbol,planet FROM fanran');
foreach ($r_s->fetchAll() as $row) {
    print "{$row['symbol']} goes with {$row['planet']} <br/>\n";
}

/*
 * 每次调用fetch()返回结果集中的下一行，当没有可用行的的时候，fetch()返回false。
 * 或者是返回一行，同时将指针指向下一行？
 * 默认情况下，fetch()返回一个数组，该数组对每一列包含两次，一次带有与列名一致的字符串索引，
 * 另一次带有一个数字索引。
 * 向fetch()传递第二个参数，可以使fetch()返回不同格式的数组，第二个参数是一个常量：PDO::FETCH_*。
 * 常见的PDO::FETCH_*有：PDO::FETCH_BOTH，PDO::FETCH_NUM，PDO::FETCH_ASSOC，PDO::FETCH_OBJ等。
 * PDO::FETCH_BOTH相当于默认，PDO::FETCH_NUM只返回数字索引数组，PDO::FETCH_ASSOC只返回字符串索引数组。
 * 注意PDO::FETCH_OBJ使得fetch()返回stdClass类对象，以列名作为属性名。
 * 还有PDO::FETCH_LAZY，返回PDORow类对象，以列名作为属性名，但是在被访问之前，这些属性却并不会出现在对象中。
 * 所以，如果你的结果行中有许多列的话，这是一个好选择。
 * 要注意如果你存储了一个返回的对象并fetch下一行的话，那么这个被存储的对象的属性值会被新行的值替代。
 */
echo 'USE QUERY AND FETCH-----------------------'.'<br />';
$ith = 1;
$rows = $db_cn->query('SELECT symbol,planet FROM fanran ORDER BY planet');
while($one_row = $rows->fetch()){
    print "The {$ith}th result are that {$one_row['symbol']} goes with {$one_row['planet']}";
    echo '<br />';
    $ith++;
}

echo 'USE bindColumn()----------------------------------';
echo '<br />';
$row = $db_cn->query('SELECT symbol,planet FROM fanran',PDO::FETCH_BOUND);
// 把'symbol'列的值放到变量$symbol中，或者说将symbol列的值与变量$symbol绑定。
$row->bindColumn('symbol', $symbol);  // 'symbol'可用1替代，列的数值从1开始。
// 把第2列('planet')的值放到变量$planet中，或说将第2列得值与变量$planet绑定。
$row->bindColumn(2, $planet);   // 2可用'planet'替代。
// 当bindColumn()之后，每当fetch()取出一行结果，绑定的变量值就会被相应的字段值填充。
while ($row->fetch()) {
    print "$symbol goes with $planet. <br/>\n";
}

$sql_table_family = <<<FAMILY_TABLE
DROP TABLE IF EXISTS `family`;
CREATE TABLE `family` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_naive` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
FAMILY_TABLE;
$mysql_cn1 = DBCnt_mysql::get('localhost','root','why1983316','mysql','3306');
$mysql_cn1->exec($sql_table_family);
// exec用于执行纯粹的SQL语句，已经见到了'创建表'、'插入'、'删除'、'更新'。
// exec执行SQL语句，然后（如果有的话）返回影响的行数。
// 然而对于有的SQL语句，exec是无意义的（尽管也能够执行），比如select，
// select并不会影响行数，同时查询的目的是要返回结果集，所以使用exec执行select语句毫无意义。
// 能够影响行数的是'插入'、'删除'和'更新'。
// 书中原话：exec()向数据库发送传递给它的任何东西。对于INSERT、UPDATE和DELETE，它返回这些查询影响的行数。
$mysql_cn1->exec("INSERT INTO family (id,name) VALUES (1,'Vito')");
$mysql_cn1->exec("INSERT INTO family (id,name) VALUES (8,'Fredo')");
$mysql_cn1->exec("INSERT INTO family (id,name) VALUES (100,'Kay')");
$mysql_cn1->exec("DELETE FROM family WHERE name LIKE 'Fredo'");
$mysql_cn1->exec("UPDATE family SET is_naive = 1 WHERE name LIKE 'Kay'");

// 使用prepare和execute可以达到与exec相同的结果。
$sql_table_fanran = <<<FANRAN_TABLE
DROP TABLE IF EXISTS `fanran`;
CREATE TABLE `fanran` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_naive` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
FANRAN_TABLE;
// prepare返回一个PDOStatement对象，execute是这个对象的方法。
$pdo_statement = $mysql_cn1->prepare($sql_table_fanran);
$pdo_statement->execute();
// 一旦准备好了查询，那么就可以用不同的值来多次执行这个查询。
$pdo_statement = $mysql_cn1->prepare('INSERT INTO fanran (id,name) VALUES (?,?)');
$pdo_statement->execute(array(1,'Vito'));
$pdo_statement->execute(array(8,'Fredo'));
$pdo_statement->execute(array(100,'Kay'));

$pdo_statement = $mysql_cn1->prepare('DELETE FROM fanran WHERE name LIKE ?');
$pdo_statement->execute(array('Fredo'));
// 注意execute函数的参数必须是一个数组。
$pdo_statement = $mysql_cn1->prepare('UPDATE fanran SET is_naive = ? WHERE name LIKE ?');
$pdo_statement->execute(array(1,'Kay'));

// prepare的参数——查询字符串中的占位符（？）决定execute参数数组中元素的位置和数量。
// 传递到execute参数数组中的值统称为绑定参数。
/*
 * 1、使用绑定参数，无需担心SQL注入攻击；
 * 2、execute的执行要比exec()或query()更加快速。
 */
echo 'THE NUMBER OF ROWS RETURNED--------------------------------'.'<br />';
/*
 * 如果你发出select命令，那么计算返回行数的唯一不会错的方法是：
 * 使用fetchAll()接收它们然后统计你得到了多少行。
 */
$pdo_st = $db_cn->query('SELECT symbol,planet FROM fanran');
$all= $pdo_st->fetchAll(PDO::FETCH_COLUMN, 1);
print "Retrieved ". count($all) . " rows";
echo '<br />';
// 如果查询的结果集太大，那么fetchAll的效率就会很低，这时可以用下面方法来替代。
$pdo_st = $db_cn->query('SELECT COUNT(*) FROM fanran');
$count_r= $pdo_st->fetch();
echo 'Retrieved '.$count_r[0].' rows';

// 使用占位符编写查询，这样prepare()和execute()就能为你转意字符串。

echo '<br />PRINT ERROR INFO--------------------------------------<br />';
/*
 * errorInfo()返回一个三元素的数组，第一个元素返回五字符的SQLSTATE错误码，这也是errorCode()返回的东西；
 * 第二个元素是数据库后端特定的错误码，第三个参数数据库后端特定的错误信息。
 */
$pdo_st = $db_cn->prepare('SELECT * FROM unkown_table');
if (! $pdo_st) {
    // errorInfo()和errorCode()的调用必须与被检测的动作的调用使用同一对象。
    // 比如这里errorInfo()要检测prepare()是否发生错误，而prepare()是通过$db_cn来进行调用的，
    // 那么errorInfo()也必须通过$db_cn来进行调用。
    /*
     * 要注意，创建PDO对象时并不能使用errorInfo()和errorCode()，因为此时你还没有能够调用errorInfo()和errorCode()的对象。
     * 这个时候要使用异常处理。
     */
    $error = $db_cn->errorInfo();
    print_r($error);
}

// 在SQLSTATE错误码中，00000意味着没有错误，所以如果调用errorCode()返回00000那么意味着成功。

/*
 * 要使得PDO在每次遭遇错误时都抛出异常，那么就要在创建PDO对象之后调用setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 * 这样你就可以统一地检测并处理PDO错误，而不必频繁使用errorCode()和errorInfo()来检测错误。
 * try...catch...从逻辑上来说，也是一种分支，把正常的功能逻辑分配在try块中，把错误处理的逻辑分配在catch块中。
 */
try {
    $db = DBCnt_sqlite::get('../col-fo-s,sd/sqliteOne.db');
    // Make all DB errors throw exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $st = $db->prepare('SELECT * FROM fanran');
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_NUM)) {
        print implode(',',$row). "<br/>\n";
    }
} catch (Exception $e) {
    print "Database Problem: " . $e->getMessage();
}

// 下面分别是mysql和sqlite创建带有唯一ID（unique ID）的表的SQL语法的方法，这个ID的值是由数据库插入的。
$mysql_cn1->exec(<<<_SQL_
DROP TABLE IF EXISTS `users1`;
CREATE TABLE users1 (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(255),
PRIMARY KEY(id)
)
_SQL_
);
$st = $mysql_cn1->prepare('INSERT INTO users1 (name) VALUES (?)');
// These rows are assigned 'id' values
foreach (array('Jacob','Ruby') as $name) {
    $st->execute(array($name));
}

$sqlite_cn1 = DBCnt_sqlite::get('../col-fo-s,sd/sqliteOne.db');
$sqlite_cn1->exec(<<<_SQL_
CREATE TABLE IF NOT EXISTS users1 (
id INTEGER PRIMARY KEY AUTOINCREMENT,
name VARCHAR(255)
)
_SQL_
);
// No need to insert a value for 'id' -- SQLite assigns it
$st = $sqlite_cn1->prepare('INSERT INTO users1 (name) VALUES (?)');
// These rows are assigned 'id' values
foreach (array('Jacob','Ruby') as $name) {
    $st->execute(array($name));
}

// 下面是自己插入唯一ID的方法，注意此时ID的字段名不应是id，而应该是其它的东西。
$sqlite_cn1->exec(<<<_SQL_
CREATE TABLE IF NOT EXISTS users2 (
id2th VARCHAR(255) PRIMARY KEY,
name VARCHAR(255)
)
_SQL_
);
$st = $sqlite_cn1->prepare('INSERT INTO users2 (id2th, name) VALUES (?,?)');
$st->execute(array(uniqid(), 'Jacob'));
$st->execute(array(md5(uniqid()), 'Ruby'));
/*
 * uniqid()生成一个极难被猜到的字符串，把这个字符串传递给md5()来限制字符的取值范围，
 * md5()会返回一个只包含数字和英文字母(a——f)的字符串。
 */

try {
    // ORDER BY sign：根据sign字段的值对查询结果进行排序；
    // LIMIT 5：只取查询结果中的前5条；
    // OFFSET 0：从偏移第一条记录0个位置的记录开始提取，偏移0意味着还是第一条记录。
    $st = $sqlite_cn1->prepare('SELECT * FROM fanran ORDER BY sign LIMIT 5 OFFSET 0');
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_NUM)) {
        print implode('---',$row). "<br/>\n";
    }
} catch (Exception $e) {
    print "Database Problem: " . $e->getMessage();
}

echo '-------------------------------Making Paginated Links for a Series of Records<br />';
/*
 * 可以利用超链接来向PHP页面发送信息，例如：
 * <a href="page.php?id=77">some link</a>；
 * 当用户点击该链接时，就会向page.php页面发送id=77这个键/值对（或称二元组）了。
 */
function print_link($is_current, $text, $offset=null) {
    if ($is_current) {
        print "<span class='current_page'>$text</span>";
    } else {
        // $_SERVER['PHP_SELF']指的是当前PHP脚本自身。
        print "<span class='other_page'>".
            "<a href='" . htmlentities($_SERVER['PHP_SELF']) .
            "?offset=$offset'>$text</a></span>";
    }
}

/*
 * indexed_links函数相当于形成一把“直尺”，$total相当于尺子的总长，$per_page相当于刻度的间隔，或说刻度间的长度；
 * 一般情况下，这两个值是不会变的，除非你换了另一把尺子。
 * $offset则相当于刻度，相邻的刻度之间必须保证$per_page这个间隔。
 * 刻度的值都是相对于第一条记录的偏移量，有了（偏移量，间隔）这个二元组，就可以查询出[刻度N，刻度N+1)这个区间的记录。
 * 当用户在客户端选中一个刻度的时候，这个刻度会传递给服务器端，服务器查询显示区间记录并重新形成这把“直尺”。
 * 新的“直尺”上会标注出被选中的刻度（无超链接），并把其它可选择的刻度（有超链接）呈现给用户。
 * 用户选择前后两把“直尺”的刻度的形成顺序是相同的，唯一区别仅在于被选中的刻度的不同。
 * 程序默认选择最小刻度。
 */
function indexed_links($total, $offset, $per_page) {
    $separator = ' | ';
    // 打印"<<Prev"链接。
    print_link($offset == 1, '<< Prev', max(1, $offset - $per_page));
    // 打印所有的分组，除了最后一个。
    for ($start = 1, $end = $per_page;
         $end < $total;
         $start += $per_page, $end += $per_page) {
        print $separator;
        print_link($offset == $start, "$start-$end", $start);
    }
    /*
     * 打印最后的分组 -
     * 在这个地方，$start指向最后一个分组开头的元素。
     */
    /*
     * 如果在最后一个分页中有多个元素，那么文本应该只包含一个范围。
     * 例如，有11条记录，每分页5条，那么最后一个分组应该只是"11"，而不是"11-11"。
     */
    $end = ($total > $start) ? "-$total" : '';
    print $separator;
    print_link($offset == $start, "$start$end", $start);
    // 打印"NEXT>>"链接。
    print $separator;
    print_link($offset == $start, 'Next >>', $offset + $per_page);
}

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 1;
if (! $offset) { $offset = 1; }
$per_page = 5;
$total = $sqlite_cn1->query('SELECT COUNT(*) FROM fanran')->fetchColumn(0);
$limitedSQL = 'SELECT * FROM fanran ORDER BY id ' .
    "LIMIT $per_page OFFSET " . ($offset-1);
$lastRowNumber = $offset - 1;
foreach ($sqlite_cn1->query($limitedSQL) as $row) {
    $lastRowNumber++;
    print "{$row['sign']}, {$row['symbol']} ({$row['id']}) <br/>\n";
}
indexed_links($total,$offset,$per_page);
print "<br/>";
print "(Displaying $offset - $lastRowNumber of $total)";
/*
 * 尺子：
 * 1、等量递增的偏移量，以偏移量为刻度；
 * 2、GET请求后“直尺”（重新）形成，从小到大依次“画”刻度；
 * 3、“直尺”形成过程中，如用户请求的刻度与将“画”之刻度相同，则进行标注；
 * 4、“直尺”形成过程中，如用户请求的刻度与将“画”之刻度不同，则形成带刻度信息的超链接。
 */
?>