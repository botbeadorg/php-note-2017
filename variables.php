<?php
/*
 条件逻辑和变量是一个计算机程序强大而灵活的精髓。
 */
/*
 当PHP解释器读取PHP文件时，如果遇到$var的时候，它认为程序员试图定义一个变量，于是PHP解释器将为该变量分配空间。
 这一步对应的C语言操作就是malloc()——动态内存分配。
 然后当PHP解释器继续往后读取，如果发现有对$var的赋值操作（$var=xxx;），那么就将值保存在$var对应的内存空间。
 并且该变量得到的赋值不是NULL，也就是说该变量有了一个值，这时候该变量就可以被使用了。
                            ———————如果一个变量有了内存空间+又有了非NULL的值，那么就说该变量被定义或者被设置。
 */
/*
 PHP有一个特殊的类型null，该类型只有一个取值NULL，NULL代表一个变量没有值。
 这时候虽然$var被分配了空间，但是空间中并没有存储有效的值，所以此时去使用这个变量会引起警告错误。
 */
 $abc;
 //一个未设置的变量也是空的。
 if (empty($abc)) {
 	echo 'be not set or be empty';
 }
 $var_int = 0;
 $var_double = 0.0;
 $var_str = "";  //或者$var_str='';
 $var1_str = "0";
 $var_bool = false;
 $var_ary = array();
 $var_null = NULL;
 //一个空变量意味着它拥有的值等价于布尔值false。上述变量中出现的值都等价于false。
 //-----------------------------------------避免将==写成=-----------------------------------------------------
 echo '<br />';
 $dwarves = 12;
 //将相等比较中的常量写在==的左侧：
 if (12 == $dwarves) {
 	echo '如果把==错写成=，会引发解析错误!';
 }
 echo '<br />';
 $dwarves1 = 'sleepy';
 if (0 == $dwarves1) {
 	echo '在不强制比较类型的情况下，整数0跟字符串sleepy相等。';
 }
 //为了进行更精确的比较，应该使用强制类型的比较===来替代==。
 //-----------------------------------------确立默认值--------------------------------------------------------
 $cars;
 $default_cars = 'Benz';
 //为尚未有值的变量指定默认值。
 if (! isset($cars)) {
 	$cars = $default_cars;
 }
 echo '<br />';
 echo $cars;
 $defaults = array('emperors' => array('Rudolf II','Caligula'),
 	'vegetable' => 'celery',
 	'acres' => 15);
 //以数组元素的键作为变量名
 //将$放在一个PHP变量之前，将会以该变量的值作为变量名来构成一个新的变量。
 //实际上在这个新变量之前又可以放置更多的$。
 foreach ($defaults as $k => $v) {
 	if (! isset($$k)) { $$k = $v; }
 }
 echo '<br />';
 print_r($emperors);
 echo '<br />';
 //---------------------------------在不需要临时变量的情况下交换变量的值---------------------------------------
 $yesterday = 'pleasure';
 $today = 'sorrow';
 $tomorrow = 'celebrate';
 list($yesterday,$today,$tomorrow) = array($today,$tomorrow,$yesterday);
 echo $yesterday.' '.$today.' '.$tomorrow;
 echo '<br />';
 //--------------------------------------------创建动态的变量名------------------------------------------------
 $stooges = array('Moe','Larry','Curly');
 $stooge_moe = 'Moses Horwitz';
 $stooge_larry = 'Louis Feinberg';
 $stooge_curly = 'Jerome Horwitz';
 foreach ($stooges as $s) {
 	//PHP计算花括号之间的表达式并把它作为变量名。
 	print "$s's real name was ${'stooge_'.strtolower($s)}.\n";
 }
 //花括号的使用能够避免产生歧义，比如$$donkeys[12]可以被解释成${$donkeys[12]}，也可以被解释成${$donkeys}[12]。
 //-------------------------------在同一函数的多次调用中维持局部变量的值---------------------------------------
 echo '<br />';
 function track_times_called() {
 	//将变量声明为static使得函数记住该变量的值，这样在对该函数的后续调用中你可以访问到这个变量的值。
 	static $i = 0;
 	$i++;
 	echo $i.'<br />';
 }
 for($i=0;$i<5;$i++){
 	track_times_called();
 }
 // static变量只能在一次脚本运行中维持局部变量的值。
 //------------------------------------将复杂数据类型封装在一个字符串中---------------------------------------
 //你希望在文件或数据库中保存一个字符串，而这个字符串能够描述一个数组或对象的数据。
 $pantry = array('sugar' => '2 lbs.','butter' => '3 sticks');
 //die()函数输出一条信息并结束当前脚本。
 //这里使用了逻辑或短路求值得特性，fopen如果失败那么会返回false。
 $fp = fopen('pantry','w') or die ("Can't open pantry");
 fputs($fp,serialize($pantry));
 //a:2:{s:5:"sugar";s:6:"2 lbs.";s:6:"butter";s:8:"3 sticks";}
 fclose($fp);
 $new_pantry = unserialize(file_get_contents('pantry'));
 print_r($new_pantry);
 //第一种方法是使用serialize和unserialize，第二种是使用json_encode和json_decode。
 echo '<br />';
 $fp = fopen('pantry.json','w') or die ("Can't open pantry");
 fputs($fp,json_encode($pantry));
 //{"sugar":"2 lbs.","butter":"3 sticks"}
 fclose($fp);
 //json_decode的第二个参数true产生一个关联数组，否则产生一个stdClass类型的对象。
 $new_pantry1 = json_decode(file_get_contents('pantry.json'), true);
 print_r($new_pantry1);
 //相对于json编码，序列化方式保存了值的类型和长度，这便于更快地解码。
 //如果字符串只是在PHP程序间传递，那么应该使用序列化方法；如果PHP程序需要与其它语言交互，那么json方式更好。
 $shopping_cart = array('Poppy Seed Bagel' => 2,
 	'Plain Bagel' => 1,
 	'Lox' => 4);
 echo '<br />';
 //当通过查询字符串的方式来传递序列化的数据时，应该保证urlencode对特殊字符进行转义。
 print '<a href="next.php?cart='.urlencode(serialize($shopping_cart)).'">Next</a>';
 //转义：改变其原有含义。
 //----------------------------------------将变量内容作为字符串输出-------------------------------------------
 $info = array('name' => 'frank', 12.6, array(3, 4));
 echo '<br />';
 echo '<pre>';
 print_r($info);
 var_dump($info);
 //var_export()函数产生的字符串是有效的PHP脚本编码。
 var_export($info);
?>