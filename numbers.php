<?php
/*---------------------检测一个变量中是否包含一个有效的数字-------------------------------*/
//使用is_numeric来检查一个变量中的数据是否是一个有效数字，或者是否是一个能转换为数字的字符串。
//对于带有千分位分隔符的整数，is_numeric会返回false，
//这个时候需要剔除千分位分隔符，方法是将千分位分隔符用空串来替换。
$number = '5,100';
echo is_numeric($number);
echo is_numeric(str_replace(',','',$number));
//检测具体类型的方法还有：is_float()，is_int()等。
/*------------------------比较浮点数------------------------------------------*/
//以二进制形式表达的浮点数只能有有限的数位来表达尾数和指数，当你超出了这些数位的时候就会溢出。
//在这种情况下，PHP并不认为两个相等的数实际上真正地完全一样，因为在数字的尽头可能会有所不同。
echo '<br />';
//使用一个较小的Δ值，检测两数之差是否小于这个Δ值。
$delta = 0.00001;
$a = 1.00000001;
$b = 1.00000000;
if (abs($a - $b) < $delta) {
	print '$a and $b are equal enough.';
}
//上述计算表示在精确到小数点后五位的精度上是相等的。
/*---------------------------浮点数舍入-----------------------------*/
echo '<br />';
$number = round(2.4);   //四舍五入
printf("2.4 rounds to the float %s", $number);
$number = ceil(2.4);  //向上舍入，天花板函数
echo '<br />';
printf("2.4 rounds up to the float %s", $number);
echo '<br />';
$number = floor(2.4);  //向下舍入，地板函数
printf("2.4 rounds down to the float %s", $number);
echo '<br />';
$number = round(2.5);
printf("Rounding a positive number rounds up: %s\n", $number);
echo '<br />';
$number = round(-2.5);
printf("Rounding a negative number rounds down: %s\n", $number);
//当一个数落在两个整数之间的时候，PHP总是向远离坐标零的方向舍入。
//因为计算机的存储方式，浮点数可能不会产生正确的值，这会带来混乱，而PHP会自动对舍入运算弥补这种误差因素。
$cart = 54.23;
$tax = $cart * .05;
$total = $cart + $tax;
$final = round($total, 2);
print "Tax calculation uses all the digits it needs: $total, but ";
print "round() trims it to two decimal places: $final";
//round()函数接受一个可选的精度参数。
/*----------------------------操作一个范围内的整数-------------------------------------*/
//使用range()函数
echo '<br />';
$numbers = range(3, 7);
foreach ($numbers as $n) {
printf("%d squared is %d\n", $n, $n * $n);
}
foreach ($numbers as $n) {
printf("%d cubed is %d\n", $n, $n * $n * $n);
}
//range()会返回一个第一个参数和第二个参数之间的数组，上面示例中好处是简洁，但坏处是一个大数组会消耗不必要的内存。
//如果你希望范围内的数据不是递增的，而是有步差，那么你可以用第三个参数来指定range的步差，另外range的起始值可以大于结束值，
//这时候，range产生的数据是递减的。
echo '<pre>';
print_r(range('l', 'p'));
echo '</pre>';
//range()还能产生字符序列，但是仅限于ASCII字符。
/*---------------------------------生成某个范围内的随机数----------------------------------------------------*/
$lower = 65;
$upper = 97;
// random number between $upper and $lower, inclusive
$random_number = mt_rand($lower, $upper);
echo $random_number;
//调用mt_rand()而不传入任何参数会返回0到最大随机数之间的一个数值，这个最大随机数由mt_getrandmax()产生。
//mt_rand()产生的数据要比rand()更具有随机性，并且更加快速，所以倾向于使用mt_rand()。
/*-----------------------------生成可预见的随机数------------------------------*/
//期望随机数产生可预见的数字以便你可以保证重复的行为。
echo '<br />';
function pick_color() {
	$colors = array('red','orange','yellow','blue','green','indigo','violet');
	$i = mt_rand(0, count($colors) - 1);
	return $colors[$i];
}
mt_srand(34534);   //注意，调整了种子之后，产生的随机数是不同的，但是在指定种子之后，每次的随机结果是相同的。
$first = pick_color();
$second = pick_color();
// 因为传递给mt_srand()一个指定值，所以可以保证每次都取到相同的颜色：red and yellow
print "$first is red and $second is yellow.";
echo '<br />';
/*--------------------------------------------带有权重的随机数--------------------------------------------*/
/*原理：假如事件A的权重为1，事件B的权重为99，那么整个样本空间100（1+99）内的随机数绝大多数都归属于事件B。*/
function rand_weighted($numbers) {
	$total = 0;
	foreach($numbers as $number => $weight) {
		$total += $weight;
		//通过累加并记录累加和来形成区间。
		//加法运算的结果作为边界值保存在数组元素中，相邻两个元素的值就构成了一个区间。
		$distribution[$number] = $total;
	}
	//产生全部样本空间内的随机数。
	$rand = mt_rand(0, $total - 1);
	foreach ($distribution as $number => $weights) {
		if ($rand < $weights) { return $number; }
	}
}
$ads = array('ford' => 12234, // advertiser, remaining impressions
	'att' => 33424, 
	'ibm' => 11237,
	'microsoft' => 98756
);
echo $ad = rand_weighted($ads);
echo '<br />';
/*-------------------------------------格式化数字运算---------------------------------------------*/
$number = 1234.56;
echo $formatted1 = number_format($number);      //进行舍入并添加了千分位分隔符。
echo '<br />';
echo $formatted2 = number_format($number, 2);     //指明小数点后位数
//如果希望为特定区域生成适当的格式，则使用NumberFormatter
$usa = new NumberFormatter("en-US", NumberFormatter::DEFAULT_STYLE);
$formatted3 =  $usa->format($number);
echo '<br />';
echo $formatted3;
//要使用NumberFormatter，则需要打开extension=php_intl.dll扩展
//在你事先不知道小数点后有多少位的时候，如果你想保留整个数字，方法：
$number1 = 31415.92653; // your number
list($int, $dec) = explode('.', $number1);
// $formatted is 31,415.92653
$formatted4 = number_format($number1, strlen($dec));
echo '<br />';
echo $formatted4;
/*-------------------------------打印货币格式--------------------------------------------*/
$number2 = 1234.56;
$usa1 = new NumberFormatter("zh-CN", NumberFormatter::CURRENCY);
$formatted5 = $usa1->format($number2);
echo '<br />';
echo $formatted5;

$usa2 = new NumberFormatter("en-US", NumberFormatter::CURRENCY);
$formatted6 = $usa2->formatCurrency($number2, 'EUR');
echo '<br />';
echo $formatted6;
//在创建NumberFormatter对象时，NumberFormatter::CURRENCY为本地化插入货币符号、小数点和千分位分隔符。
//可以用formatCurrency()指定不同于本地货币的正确格式。
/*--------------------------------打印正确的复数---------------------------------------*/
$number = 4;
echo '<br />';
print "Your search returned $number " . ($number == 1 ? 'hit' : 'hits') . '.';
/*--------------------------------处理极大数和极小数-----------------------------------*/
//使用BCMath或者GMP库，GMP库需要打开extension=php_gmp.dll扩展。
echo '<br />';
print $sum = bcadd('1234567812345678', '8765432187654321');
$sum1 = gmp_add('1234567812345678', '8765432187654321');
//$sum1是一个GMP资源，而不是一个字符串，使用gmp_strval()进行转换。
echo '<br />';
print gmp_strval($sum);
//BCMath库易于使用，你把你的数值作为字符串传入，则函数就把结果作为字符串返回，然后该库只提供基本算数操作。
//GMP系列函数将整数、字符串或者GMP资源作为参数传入，而返回一个GMP资源，GMP资源实际上指的是数据在计算机内部的表达形式。
//你需要把GMP资源转换为字符串或整数：gmp_strval()或gmp_intval()。
/*-------------------------------进制转换---------------------------------------------*/
echo '<br />';
$hex = 'a1';
//将一个表示数字的字符串转成另一种进制下的字符串，第二个参数是当前进制，第三个参数是目标进制。
echo $decimal = base_convert($hex, 16, 10);
echo '<br />';
/*--------------------------------非10进制数字的计算-----------------------------------*/
//在数字前面放置前导的符号来让PHP知道这没有使用十进制。0b表示二进制，0表示八进制，0x表示十六进制。
for ($i = 0x1; $i < 0x10; $i++) {
	print "$i\n";
}
echo '<br />';
//虽然以非10进制的形式执行计算，但结果默认仍以10进制来表现。这是用需要数制转换来打印其它数制下的值。
for ($i = 0x1; $i < 0x10; $i++) { print dechex($i) . "\n"; }
//times 33是将一个任意长度的字符串HASH成一个整数值的方法。
function times_33_hash($str) {
	//HASH值
	$h = 5381;
	for ($i = 0, $j = strlen($str); $i < $j; $i++) {
		//左移五位相当于乘以32。
		$h += ($h << 5) + ord($str[$i]);
		//只保留$h的低32位。
		$h = $h & 0xFFFFFFFF;
	}
	return $h;
}
echo '<br />';
echo times_33_hash("Once, I ate a papaya.");
?>