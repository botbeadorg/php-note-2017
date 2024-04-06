<?php
//要在变量中存储一组相关的项，就使用数组。
//通常，每个新项都会成为数组的最后一项，当然你也可以把它楔入已有的两项之间。
//PHP数组的索引即是元素在数组内的位置，该位置从0开始。
//在PHP中，数字索引数组（简称数组）和关联数组的界限很模糊。
//数组定义的三种基本形式：
/*第一，使用array()函数-------------------------------------------------------*/
$fruits1 = array('Apples', 'Bananas', 'Cantaloupes', 'Dates');
/*第二，从PHP 5.4开始，可以使用方括号的形式，这是array()的短语法--------------*/
$fruits2 = ['Apples', 'Bananas', 'Cantaloupes', 'Dates'];
/*第三，使用追加元素的形式---------------------------------------------------*/
$fruits3[] = 'Apples';
$fruits3['Dennis'] = 'Ritchie';
$fruits3[] = 'Bananas';
$fruits3[] = 'Cantaloupes';
$fruits3[] = 'Dates';
$fruits3['iamcoming'] = 'who';
$ary_name = 'fruits';
for($i=1;$i<4;$i++){
	echo '<pre>';
	//可变变量用法类似C语言的宏展开，注意花括号的使用。
	var_export(${$ary_name.$i});
	echo '</pre>';
}
//在现有索引上的元素赋值是修改，为非现有索引赋值是追加。
//删除数组元素的方法是unset：
unset($fruits3[3]);
//array_shift()安全地移除首元素，array_pop()安全地移除尾元素。
print_r($fruits3);
//foreach($ary as [$key =>] $key_value){}
//每次迭代之前，foreach从$ary依次取出一个元素（键值对），键赋给$key，值赋给$key_value，
//直到取完所有元素，然后结束循环。
list($red, $yellow, $beige, $brown) = $fruits1;
//通过list()构造变量列表，并使用数组来为它们赋值。
echo '<br />';
echo "$red--$yellow--$beige--$brown";
/*--------------------指定数组开始于非0索引------------------------------------------------*/
echo '<pre>';
$presidents = array(1 => 'Washington', 'Adams', 'Jefferson', 'Madison');
print_r($presidents);
//数组索引甚至可以是负数。
$us_leaders = [-1 => 'George II', 'George III', 'Washington'];
print_r($us_leaders);
/*--------------------在每个键中存储多个元素----------------------------------*/
$fruits4['red'][] = 'strawberry';
$fruits4['red'][] = 'apple';
$fruits4['yellow'][] = 'banana';
echo '<br />';
print_r($fruits4);
/*-------------------检测一个键是否在数组中-----------------------------------------------------*/
//使用array_key_exists()检测一个键而不关心该键的值
if(array_key_exists('Dennis',$fruits3)){
	echo "<br /> Ritchie is in fruites4";
}
//使用isset()确定一个键的值存在但不能为null。
if (isset($fruits3['iamcoming']))echo "<br /> who is in fruites.";
/*--------------------检测一个值是否在数组中----------------------------------------------------*/
echo '<br />';
$book_collection = array('Emma', 'Pride and Prejudice', 'Northhanger Abbey');
$book = 'Sense and Sensibility';
//使用in_array来检查一个值是否在数组中。
if (in_array($book, $book_collection)) {
	echo 'Own it.';
} else {
	echo 'Need it.';
}
$array = array(1, '2', 'three');
echo '<br />'.in_array(0, $array); // true!
echo '<br />'.in_array(0, $array, true); // false
//默认情况下，in_array使用==来进行比较，
//向第三个参数传入true使得in_array使用===进行比较，即不但比较值，也比较类型。
//在上文中，PHP尝试把字符串three转换为数字，转换失败，把它认为是0。
$book_collection1 = array('Emma', 'Pride and Prejudice', 'Northhanger Abbey');
//array_flip()将数组元素的值与键互换位置，然后用isset()在键里面查找。
//将对值的查找变成了对键的查找。
$book_collection1 = array_flip($book_collection1);
print_r($book_collection1);
$book1 = 'Sense and Sensibility';
if (isset($book_collection1[$book1])) {
	echo 'Own it.';
} else {
	echo 'Need it.';
}
//使用in_array()在数组中查找消耗的时间线性增长，而对于关联数组，消耗固定时间。
echo 'three'+3;  
//遇到+号的时候，字符串自动向数字转换，
//由于字符串连接运算符为句点，所以在这里数字不会向字符串转换。
/*------------------------搜索值在数组中的位置---------------------------------------------------*/
echo '<br />';
$favorite_foods = array(1 => 'artichokes', 'bread', 'cauliflower', 'deviled eggs');
$food = 'cauliflower';
//array_search会返回找到的值的键（数字或字符串），如果找不到，返回false。
$position = array_search($food, $favorite_foods);
//在PHP中，数字零被认为是false，这跟C++一致。
if ($position !== false) {
	echo "My #$position favorite food is $food";
} else {
	echo "Blech! I hate $food!";
}
//相对in_array()，array_search能够返回有有用的信息，而且速度差异很小，推荐使用array_search。
/*-------------------------找到符合某种条件的元素-------------------------------------------------*/
//注意，这里既然涉及到条件，那么array_filter的第二个参数（过滤函数）应该返回布尔值。
echo '<br />';
//一种方法是使用foreach循环，另一种是使用array_filter()。
$num = array_filter($array, function($elem){
	//array_filter将返回使条件为真的元素。
	return is_numeric($elem);
});
//同foreach一样，array_filter会把数组中的每个元素作为参数传递给过滤函数。
echo 'After filtering:'.'<br />';
print_r($num);
//推荐使用foreach。
/*--------------------------找到数组中的最大/最小值----------------------------*/
echo '<br />'.'The Max value is '.max($favorite_foods);
echo "<br />The Min value is ".min($favorite_foods);
//如果希望找到最大/最小值对应的索引，则需要使用arsort()或者asort()函数，
//从而将最大/最小值对应的元素排在数组索引0的位置上。
//其中，arsort中的a指的是association，即保持键与值的关联。
/*--------------------------反转数组--------------------------------------------*/
echo '<br />';
$array2 = array('Zero', 'One', 'Two');
print_r(array_reverse($array2));
/*--------------------------排序数组---------------------------------------------*/
$states = array('Delaware', 'Pennsylvania', 'New Jersey');
//注意sort函数返回的是布尔值，sort会将数组元素从低到高排列。
rsort($states);
print_r($states);
$scores = array(1, 10, 2, 20);
sort($scores, SORT_NUMERIC);
//在第二个参数设置排序标志。
print_r($scores);
//sort()函数不会保留键值对的关联，它会重建索引
$tests = array('test1.php', 'test10.php', 'test11.php', 'test2.php');
//自然排序，以人类习惯的方式排序
natsort($tests);
print_r($tests);
/*----------------------------数组元素的迭代---------------------------------------*/
$foo = array("bob", "fred", "jussi", "jouni", "egon", "marliese");
$bar = each($foo);
print_r($bar);
//除了使用最简易的foreach，还可以
reset($foo);   //设置数组内部指针指向首元素，并返回首元素的值。
while(list($key, $value) = each($foo)){
	echo "The key is $key, the value is $value".'<br />';
}
//each返回数组内部指针指向的当前键值对，并将指针后移一位。
//each()返回一个数组，这个数组中有两个数字索引零0和壹1，两个字符串索引'key'和'value'。
//其中0和key，1和value的值分别相等，这样可以以数字或字符串两种索引形式来访问数组元素了。
/*----------------------------使用定制的比较函数进行排序----------------------------------*/
$tests = ['test1.php', 'test10.php', 'test11.php', 'test2.php'];
usort($tests, function ($a, $b) {
	return strnatcmp($b, $a);
});
print_r($tests);
//开头的u指的是user-defined，用户定义的；
//usort假定自定义排序函数返回负一，通过返回负一的表达式中两变量的大小关系；
//推断出函数俩形参的大小关系，从而形成升序/降序排列。
//参数一小于参数二为升序，参数一大于参数二为降序。
$test1 = [
	[3,6,'zhong',88],
	['me','ni',0,20],
	[99,'shuo',255,255,196,128],
	[127,0,'shui'],
	['le','ne']
];
//上述为创建PHP多维数组的另一种方式。
usort($test1, function($a, $b){
	$c = sizeof($a);$d=sizeof($b);
	if($c < $d)return -1;
	if($c == $d)return 0;
	if($c > $d)return 1;
});
//var_export产生能够定义这个导出变量的等价的有效PHP代码。
var_export($test1);
echo '<br />';
/*----------------------------把数组转换成字符串--------------------------------------*/
//使用join：
echo join(',',$foo);
//join()要比循环更快一些，join只是把分隔符放置在两个元素之间。
//上面这个连接字符串的操作相当于：
$result_str = '';
foreach ($foo as $key => $value) {
	$result_str .= ",$value";
}
$result_str = substr($result_str, 1);
echo '<br />'.$result_str;
//尽管最后不得不剔出多余的部分，但这也比在循环内部加入判断逻辑更加简洁有效。
//另外，前置分隔符要比后置分隔符运算更快，因为从前面缩短字符串要比从后面缩短更快。
function array_to_comma_string($array) {
	switch (count($array)) {
	case 0:
		return '';
	case 1:
		return reset($array);
	case 2:
		return join(' and ', $array);
	default:
		$last = array_pop($array);
		return join(', ', $array) . ", and $last";
	}
}
echo '<br />';
$thundercats = ['Lion-O', 'Panthro', 'Tygra', 'Lion-O', 'Cheetara', 'Snarf'];
print 'ThunderCat good guys include ' . array_to_comma_string($thundercats) . '.';
//在这段代码中，使用reset()来替代$array[0]，array_pop()来替代$array[count($array)-1]；
//究其原因，是因为可以为PHP数组元素指定索引，这是与C数组的巨大不同。
/*----------------------------移除数组中的重复元素------------------------------------*/
//对于创建好的数组：
$unique = array_unique($array);
//避免添加重复元素，第一种方法是检测一个值是否存在于数组中，不存在则追加：
$thundercats1 = array();
foreach ($thundercats as $fruit) {
	if (!in_array($fruit, $thundercats1)) { $thundercats1[] = $fruit; }
}
print_r($thundercats1);
//第二种方法专用于关联数组，以值为键。
foreach ($thundercats as $fruit) {
	//某值第一次出现是添加，若该值第二次出现就是修改了，只是改的值跟原值相同。
	$thundercats2[$fruit] = $fruit;   
}
print_r($thundercats2);
//使用第二种方法要比使用第一种方法更快。
/*------------------------对数组的每个元素应用同一个函数------------------------------*/
$names = array('firstname' => "Baba", 'lastname' => "O'Riley");
//使用引用传值（在形参前加&），将函数内的操作作用于实参本身而非副本，其行为与C++类似。
array_walk($names, function (&$value, $key) {
	$value = htmlentities($value, ENT_QUOTES);
});
foreach ($names as $name) {
	print "$name\n";
}
//array_walk()有一个用于嵌套（多维）数组的版本——array_walk_recursive()。
/*------------------------求两个数组的合、交、差集-------------------------------------*/
$union = array_unique(array_merge($thundercats, $names));
$intersection = array_intersect($thundercats, $names);
$difference = array_diff($thundercats, $names);
/*------------------------将一个数组追加到另一个数组上-----------------------------------*/
$p_languages = array('Perl', 'PHP');
//使用array_merge函数
$p_languages = array_merge($p_languages, array('Python'));
print_r($p_languages);
//合并只有数字索引（数字键）的数组使得数组能够重建索引，所以不会出现值的丢失。
//合并有字符串键的数组会使第二个数组覆盖重复键的值。
//包含两类键的数组在合并时会展现出两种不同的行为。
$lc = array('a', 'b' => 'b'); 
$uc = array('A', 'b' => 'B'); 
$ac = array_merge($lc, $uc); 
print_r($ac);
//数字键部分从0开始重建索引，而字符串键部分则发生覆盖。
//另外加号运算符（+）也可以用来归并数组，然而重复的键（无论数字还是字符串）都会发生覆盖。
print_r($uc + $lc);
print_r($lc + $uc);
//规则是发生覆盖时，使用表达式左侧数组的元素值。
/*------------------------------------改变数组大小-------------------------------------------------------*/
$array_s = ['apple', 'banana', 'coconut'];
//在尾部扩展数组，将长度扩展为5，扩展出的新元素赋值为'at end'。
$array_s1 = array_pad($array_s, 5, 'at end');
print_r($array_s1);
//扩展数组，长度为4，新加元素添加在数组头部。
$array_s2 = array_pad($array_s, -4, 'at head');
print_r($array_s2);
$array_s3 = ['apple', 'banana', 'coconut', 'dates'];
array_splice($array_s3, 3);
print_r($array_s3);
array_splice($array_s3, -1);
print_r($array_s3);
//splice是铰接、捻接的意思，array_splice函数第二个参数的作用是产生一个接点，接点之后的部分就被去掉了。
/*--------------------------------不使用临时变量交换值--------------------------------------------------*/
$a = 'Alice';
$b = 'Bob';
list($a,$b) = array($b,$a);
//将变量的值存在数组当中以免丢失，那么这个数组就起到了临时变量的作用。
//PHP的list()语言结构允许你把来自数组的值赋值给单独的变量。
//list仅适用于数字索引的数组（或数组的数字索引部分），并假定索引从零开始。
echo "$a   $b";
/*---------------------------------重建数组索引---------------------------*/
//array_values()返回一个数组的全部值，并为新数组建立与C语言相符的数字索引。
$fruits5 = array_values($fruits3);
echo '<br />';
print_r($fruits5);
//使用array_values可以移除数组中的空洞（形容不连续的数值索引）。
/*----------------------同时遍历两个数组的方法----------------------------*/
$ary1=['a'=>44, 'b'=>89, 'ni'=>'2221a', 'shi'=>'weidade'];
$ary2=['9681a'=>'zen', 'intel'=>998, 67=>'shishui'];
reset($ary1);
reset($ary2);
while(list($key,$value) = each($ary1))
{
	if(false != (list($key1,$value2) = each($ary2))){
		$ary1[$key]=$value2;
	}else
		break;
}
print_r($ary1);
/*-----------------将复杂数据（数组或对象）以字符串形式表达------------------*/
// 序列化——转变为字符串；
$str_of_ary = serialize($ary2);
echo '<br />'.$str_of_ary;
// 反序列化——转变为数组。
$ary_of_str = unserialize($str_of_ary);
echo '<br />';
print_r($ary_of_str);
// JSON化——转变成JSON结构；
$json_of_ary = json_encode($ary2);
echo '<br />'.$json_of_ary;
// 反JSON化——转变成数组。
// json_decode需要第二个参数来声明将json字符串转为数组，
// 如果省略了第二个参数，则自动转换成对象stdClass Object{}。
$ary_of_json = json_decode($json_of_ary, true);
echo '<br />';
print_r($ary_of_json);
/*-------------------使用list选取数组的部分值------------------*/
function time_parts($time) {
	return explode(':', $time);
}
list(, $minute, ) = time_parts('12:34:56');
echo $minute;
//list通过逗号来判定变量接收数组的哪个元素，因此逗号不能省略。
/*---------------------------------------以数组的方式来访问对象----------------------------------------------*/
// ArrayAccess接口可以让你以数组的方式来访问对象内的数据。
class FakeArray implements ArrayAccess {
	// 设为私有意味着只能通过存取器来访问；
	private $elements;
	public function __construct() {
		// 创建空数组来存储数据；
		$this->elements = [];
	}
	// 实现ArrayAccess的接口函数。
	public function offsetExists($offset) {
		return isset($this->elements[$offset]);
	}
	public function offsetGet($offset) {
		return $this->elements[$offset];
	}
	public function offsetSet($offset, $value) {
		return $this->elements[$offset] = $value;
	}
	public function offsetUnset($offset) {
		unset($this->elements[$offset]);
	}
	// 上述四函数皆以offset（偏移量）做名字前缀，意味着通过偏移量（索引）进行操作。
	// C语言数组正是以相对数组首元素的偏移量为索引的，首元素相对于它自身的偏移量为零，依次类推。
}
echo '<br />';
$aryx = new FakeArray;
// 赋值——>offsetSet；
$aryx['first'] = 'value1';
// isset——>offsetExists；
if(isset($aryx['first'])){
	echo 'find it!'.'<br />';
}
// 访问元素——>offsetGet；
echo $aryx['first'];
echo '<br />';
for($i=0;$i<3;$i++)$aryx[$i]=$i;
// unset——>offsetUnset。
unset($aryx[1]);
print_r($aryx);
/*----------------------------------------在数组某个位置插入元素--------------------------------------------*/
array_splice($thundercats, 3, 0, 'CHARU');
print_r($thundercats);
?>
