<?php
// 使用关键字function开始定义函数，之后是函数名字以及圆括号内的形式参数列表。
// 调用函数只需要使用函数名与圆括号内的实际参数列表。
// 如果函数使用return来返回值，你可以将函数的结果赋值给一个变量。
// 在PHP中，函数的定义可以出现在调用之后，因为PHP在开始执行之前会解析整个PHP文件。
function add($a, $b) {
	return $a + $b;
}
$total = add(2, 2);
echo '<pre>';
echo $total.'<br />';
/*----------------------------访问函数参数------------------------------------------*/
// 在函数内部，PHP不关心值是以哪种变量形式（字符串、数组、数值等）传入的，
// 你只需要以函数原型中的变量名来引用它们就行了。
function commercial_sponsorship($letter, $number) {
	print "This episode of Sesame Street is brought to you by ";
	print "the letter $letter and number $number.\n";
}
$another_letter = 'X';
commercial_sponsorship($another_letter, 15);
// 对象默认通过引用进行传递。
// 除非特别说明，通过函数传入，或从函数返回的非对象数据都是通过值进行传递的。
// 这意味着PHP会复制一份数据，并把这份副本提供给你进行访问和操作。
// 因此你对这份副本的操作不会影响到原本。
function add_one($number) {
	$number++;
}
$number = 1;
add_one($number);
print $number;
// 通过引用传递要比值传递更快一点，不过在PHP上这种速度差异非常小。
// 建议只在必要的时候使用引用传递，并且不要把引用传递作为一种速度优化的技巧。
/*--------------------------为函数参数设置默认值-------------------------------*/
function wrap_in_html_tag($text, $tag = 'strong') {
	return "<$tag>$text</$tag>";
}
echo '<br />';
echo wrap_in_html_tag("Hey, a mountain lion!");
// 默认值只能出现在所有非默认值的右侧，默认值只能是常数值。
/*----------------------------------通过引用传值------------------------------*/
// 引用传值实际上是修改了值传递的行为，没有经过拷贝而直接把原本传递到函数中。
function wrap_in_html_tag_ref(&$text, $tag = 'strong') {
	//注意参数列表中引用传值形参前的&，这点跟C++一致。
	$text = "<$tag>$text</$tag>";
}
echo '<br />';
$contt = 'Hey, big big man!';
wrap_in_html_tag_ref($contt);
echo $contt;
// 引用传值必须传入一个变量，C++引用变量是一种缺失了地址属性的变量。
/*
  C/C++变量的三个属性：
  地址——变量在内存中的起始位置；
  类型——变量占据多大的内存空间，以及，编译器如何解释变量的值；
  值——变量存储什么内容。 
 */
/*-------------------------------------参数的类型提示----------------------------------*/
// 保证传递给函数的值必须属于某种特定类型。
// 语法跟C/C++一样，在形参前面加上类型。
function array_or_null_is_ok(array $fruits = null) {
	if (is_array($fruits)) {
		foreach ($fruits as $fruit) {
			print "$fruit\n";
		}
	}
}
// 并不是所用的类型都能作为类型提示，只有类名、接口名，关键字array才能用作类型提示。
// 如果传入的值不满足类型提示，那么PHP会触发E_RECOVERABLE_ERROR致命错误。 
/*---------------------------------创建具有可变数目参数的函数------------------------------*/
// 基本思路：将参数组织在数组中，然后传入这个数组。
function mean($numbers) {
	$sum = 0;
	$size = count($numbers);
	for ($i = 0; $i < $size; $i++) {
		$sum += $numbers[$i];
	}
	$average = $sum / $size;
	return $average;
}
echo '<br />';
print_r($mean = mean(array(96, 93, 98, 98)));
// 不需要指定参数列表。
function mean1() {
	$sum = 0;
	// func_num_args()返回它所在的函数被调用时传入的参数个数。
	$size = func_num_args();
	for ($i = 0; $i < $size; $i++) {
		// 使用func_get_arg() 找到某个位置上的参数。
		$sum += func_get_arg($i);
	}
	$average = $sum / $size;
	return $average;
}
echo '<br />';
echo mean1(96, 93, 98, 98);
function mean2() {
	$sum = 0;
	$size = func_num_args();
	// func_get_args()返回包含传递到它所在函数的所有参数的数组。
	foreach (func_get_args() as $arg) {
		$sum += $arg;
	}
	$average = $sum / $size;
	return $average;
}
echo '<br />';
echo mean2(96, 93, 98, 98);
/*-------------------------------通过引用来返回值-----------------------------------*/
// 通过引用来返回值避免在赋值时再次发生拷贝。
// 注意在函数名前面要有与号。
// 跟引用参数一样，避免复制，返回原本。
function &array_find_value($needle, &$haystack) {
	foreach ($haystack as $key => $value) {
		if ($needle == $value) {
			return $haystack[$key];
		}
	}
}
// 感觉PHP的引用应是从C++中借鉴过来的。
$minnesota = array('Bob Dylan', 'F. Scott Fitzgerald', 'Prince', 'Charles Schultz');
// 另外必须使用=&来接收通过引用返回值。
$prince =& array_find_value('Prince', $minnesota);
$prince = 'O(+>';
echo '<br />';
print_r($minnesota);
// 对于对象变量来说，赋值运算（=）使得两者引用相同的对象，或者说引用的复制；
// 对于非对象变量来说，赋值运算意味着内容的复制。
/*********************************************************************************************
C语言的参数传递只有一种形式————复制值（或者说传值）。
但如果把一个变量的地址值复制到参数中，那么在函数内就可以通过这个复制进来的地址找到对应变量。
那么对于这个变量来说，传递的不是它本身的值，而是它的地址值（亦即能够找到该变量的值）。
这种不拷贝变量本身的值，只拷贝变量地址值（并通过地址来访问变量）的方式就进化出了C++引用。
PHP同时沿用了传值和C++引用这两种参数传递方式。
*********************************************************************************************/
/*---------------------------------一次返回多个值--------------------------------------*/
// 常规思路就是返回一个数组。
function time_parts($time) {
	return explode(':', $time);
}
// 将返回值用list()来承接，list()本身返回一个数组。
print_r(list($hour, $minute, $second) = time_parts('12:34:56'));
function time_parts1($time) {
	return explode(':', $time);
}
list(, $minute,) = time_parts1('21:54:59');
echo $minute;
// list通过逗号来判定变量接收数组的哪个元素，因此逗号不能省略。
// 如果数组中有N（N >= 1）个元素，那么list()之中就要有N-1个逗号。
/*-----------------------------------从函数中返回失败---------------------------------------*/
// 在PHP中，等价于逻辑假的值并没有标准化，因此很容易产生错误。
// 你的函数应该明确的返回已定义的false关键字，这是进行逻辑判断的最好方法。
/*-----------------------------------使用call_user_func调用函数-------------------------------------*/
// 实际上相当于C语言中通过函数指针来调用函数。
// 将函数名字作为字符串传递给call_user_func的第一个参数，将函数的参数作为call_user_func的第二个参数。
print_r(call_user_func('time_parts', '18:37:58'));
// 如果被调用的函数需要多个参数，则可以用call_user_func_array()，将参数组织成数组传给第二个参数。
echo call_user_func_array('mean1', [137, 157, 126, 95, 96]);
/*----------------------------------在函数内部访问全局变量------------------------------------------*/
// 全局变量指的是同一个PHP文件内，定义在任何函数之外的变量。
// 定义全局变量的操作实际上是在PHP预定义数组$GLOBALS中添加键值对的操作。
$food = 'pizza';  //相当于$GLOBALS['food'] = 'pizza';
$drink = 'beer';
function party() {
	// 1、在函数内部可以使用global关键字来引用全局变量。
	global $food, $drink;
	// 只是删除了这个引用。
	unset($food);
	// global相当于做出了如下操作，相当于复制了地址。
	$food2 =& $GLOBALS['food'];
	echo $food2.='s';
	// 2、直接使用$GLOBALS数组。
	unset($GLOBALS['drink']);
	unset($food2);
}
echo '<br />';
print "$food: $drink\n";
party();
echo '<br />';
print "$food: $drink\n";
/*
 * PHP是一门解释型语言，这意味着需要一个工具来读取脚本并根据内容进行相应的操作。
 * 这有点类似于一个C语言程序读取一个文本文件然后根据其中的内容执行对应的动作。
 * 那么应该是边读边进行动作的。对于变量来说，那么读到变量定义就会分配内存空间并赋值，
 * 很明显这个过程是动态的，相当于malloc/new，而读到unset就会删除变量，也就相当于free/delete。
 * PHP数组似乎是通过链表这种松散的数据结构来实现的。所以不设定大小。
 * PHP对同一引用有两种解释，一种是“引”，一种是“用”。
 */
/*-----------------------------------根据运行情况创建函数---------------------------------------*/
$increment = 7;
// 相当于使用函数指针来调用函数。
// 这里的use()指的是要在函数内部使用这个全局变量。
$add = function($i, $j) use ($increment) { return $i + $j + $increment; };
echo $sum = $add(1, 2);
// 上述语法称之为闭包，如果没将function的结果保留，那就是创建了一个匿名函数。
?>