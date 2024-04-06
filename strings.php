<?php
//--------------------------------------------前言----------------------------------------
/*
 * PHP strings are binary-safe (i.e., they can contain null bytes) and can grow and shrink on demand.
 * Their size is limited only by the amount of memory that is available to PHP.
 * PHP字符串是二进制安全的，并且可以按需增长或收缩。
 * 它们的尺寸仅受限于PHP可用的内存大小。
 */
/*
 * With single-quoted strings,
 * the only special characters you need to escape inside a string are backslash and the single quote itself.
 * 对于单引号字符串，在一个字符串中唯二需要转义的字符就是反斜杠和单引号本身。
 */
    echo 'Escape the single-quote \' character itself.';
    echo '<br />';
    echo 'Escape the backslash \\ character.';
/*
 * Because PHP doesn't check for variable interpolation or almost any escape sequences in single-quoted strings,
 * defining strings this way is straightforward and fast.
 * 由于PHP不会检查单引号字符串中几乎任何转义序列的变量插值，所以定义单引号字符串是直接而快速。
 * 这个variable interpolation就相当于使变量的值参与表达式运算。
 */
/*
 * Double-quoted strings don't recognize escaped single quotes,
 * but they do recognize interpolated variables and the escape sequences.
 * 双引号字符串不会识别转义的单引号（\'），但是确实可以识别出PHP变量及下列转义序列。
 */
    echo '<br />';
    echo "A \\\\ stands for Backslash Character."; //中文"八"字的第一笔为斜杠，第二笔就是反斜杠。
    echo '<br />';
    echo "A \\n stands for Newline Character.";
    echo '<br />';
    echo "A \\r stands for Carriage return Character.";
    echo '<br />';
    echo "A \\t stands for Tab Character.";
    echo '<br />';
    echo "A \\$ stands for Dollar Character."; //PHP的变量都是由$开头的，并且在双引号中，如果不对$转义的话，则有可能被认为是一个变量。
    echo '<br />';
    echo "A \\\" stands for Double quotes Character."; //注意双引号本身也需要转义。
    echo '<br />';
    echo "\\0~\\777 stands for value of a character in Octal.";
    echo '<br />';
    echo "\\x0~\\xFF stands for value of a character in Hex.";
    echo '<br />';
//无论单引号还是双引号字符串，都要对该串自身所使用的引号进行转义，并对转义字符（反斜杠\）进行转义。
/*
 * Heredoc -specified strings recognize all the interpolations and escapes of double-quoted strings,
 * but they don't require double quotes to be escaped. Heredocs start with <<< and a token.
 * That token (with no leading or trailing whitespace),
 * followed by semicolon a to end the statement (if necessary), ends the heredoc.
 * Heredoc：能够识别出特定于双引号字符串中的所有转义字符，以及变量插值，但无需使用双引号包裹字符串。
 * Heredocs开始于<<<和一个标记（这个标记没有前导或后置的空白）。
 * 然后用这个标记和一个追加的分号来结束这个Heredoc。
 */
$pets=<<< ILICKEPETS
If you like pets, yell out:
"Dog"s AND "Cat"s ARE GREAT!
ILICKEPETS;
print $pets;
//按照惯例，标记通常是大写的，并且它是大小写敏感的。
/*
 * Heredocs are especially useful for printing out HTML with interpolated variables,
 * since you don't have to escape the double quotes that appear in the HTML elements.
 * Heredoc在输出带有PHP变量的HTML片段时特别有用，因为你不必转义HTML元素中出现的双引号。
 */
    echo '<br />';
    $neighbor = 'Hilda';
    //可以使用方括号来访问字符串中的单个字符，方括号中的数值是字符在字符串中的索引，索引与C语言一样，从0开始。
    echo $neighbor[3];
    //也可以使用花括号来访问字符串中的单个字符，它在字符串索引和数组索引之间提供了一个可视化的差别。
    echo $neighbor{2};
//----------------------------------------访问子串------------------------------------
echo '<br />';
if (strpos('php@varmay.com', '@')) {
    echo 'There was @ in the e-mail address!';
}
//如果第二个参数对应的字符串出现在第一个参数对应的字符串中，那么返回第二个参数在第一个参数中的索引。
//----------------------------------------提取子串------------------------------------
$main_string="Hello,World!";
$starting_index=3;
$substr_length=5;
echo '<br />';
echo substr($main_string,$starting_index,$substr_length);
/*
 * PHP的索引同C语言索引一样，也是一个元素相对于首元素的偏移量，首元素对于它自身的偏移量为0，所以索引为0。
 * 字符串也是线性的，如果起始索引大于字符串字符的最大索引，那么认为是无效的参数，结果返回false。
 * 如果缺失了长度参数，那么返回的子串则从起始索引处一直到主串最后。
 * 如果起始索引+长度超过了主串长度，则效果等同于缺失长度参数。
 * 判断计算过程时，起始索引参数的优先级更高。
 */
echo '<br />';
print (substr('watch out for that tree',-6));
/*
 * 如果起始索引为负值，则表示最后一个字符作为字符串的首元素，该字符索引为0，其它元素的索引为相对于最后一个字符的偏移量。
 * 如果长度参数为负值，那么该参数不再表示长度，而表示相对于最后一个元素的偏移量，也就是子串的结束索引。
 */
echo '<br />';
print (substr('watch out for that tree',-13,-1));
/*
 * 总结一下就是，负数的时候，两个参数都是表示索引；正数的时候，一个表示索引，一个表示长度。
 * 起始索引有更高优先级，字符串是线性增长的。
 * 第二个参数一定表示索引，第三个参数为正则表示长度，为负责表示索引。
 */
//----------------------------------------替换子串------------------------------------
$org_string='My pet is a blue dog.';
$new_substr='green';
$starting_index1=12; //如果索引为负值，仍然是从最后一个元素计算偏移量/索引。
$length_replaced=4; //如果省略长度，则被替换的子串一直到主串最后。
echo '<br />';
echo substr_replace($org_string,$new_substr,$starting_index1,$length_replaced);
//如果索引和长度都是0，则表示将子串插入到主串的头部。
echo substr_replace($org_string,'<br />',0,0);
//----------------------------------------处理串中每个字符------------------------------------
//计算字符串中元音出现的次数，使用for循环。
$string = "This weekend, I'm going shopping for a pet chicken.";
$vowels = 0;
for ($i = 0, $j = strlen($string); $i < $j; $i++) {
    if (strstr('aeiouAEIOU',$string[$i])) $vowels++;
}
echo '<br />';
echo $vowels;
//--------------------------------------反转字符串--------------------------------------
echo '<br />';
echo strrev('This is not a palindrome.');
//按单词反转。
$s = "Once upon a time there was a turtle.";
// 用第一个元素（字符串）来分割第二个参数（字符串），返回一个数组。
$words = explode(' ',$s);
// 用array_reverse反转数组元素。
$words = array_reverse($words);
// 用第一个参数（字符串）来连接第二个参数（数组）中的元素。
$s = implode(' ',$words);
echo '<br />';
print($s);
//--------------------------------------控制大小写--------------------------------------
echo '<br />';
//对整个字符串的首字母大写。
print ucfirst("how do you do today?");
print('<br />');
//对每个单词的首字母都大写。
print ucwords("the prince of wales.");
//使用strtolower( )或strtoupper( )来对所有字符进行大小写。
print strtoupper("i'm not yelling!");
print strtolower('<A HREF="one.php">one</A>');
//--------------------------------------空格与制表符替换--------------------------------------
$r = "SELECT message FROM messages WHERE id = 1";
//str_replace在第三个参数中搜索所有的第一个参数，并将其全部替换为第二个参数。
$tabbed = str_replace(' ',"\t",$r);
$spaced = str_replace("\t",' ',$tabbed);
echo '<br />';
print "With Tabs: <pre>$tabbed</pre>";
print "With Spaces: <pre>$spaced</pre>";
//--------------------------------------在字符串中存储二进制数据--------------------------------------
$packed = pack('S4',1974,106,28225,32725);
echo $packed;
$unpacked=unpack('S4',$packed);
echo '<pre>';
print_r($unpacked);
echo '</pre>';
/*
 * pack用于将整数值保存在连续的字节序列中，以字符串的形式存在。
 * 由于PHP没有细分的数据类型，所以这里需要指定数据类型（所占用的字节空间、有符号/无符号，以保证足以容纳要保存的数据），
 * 另外由于字节地址序列和数据位权序列的关系，还要指定字节序，这样一来，就接近C语言中数组的存储形式了。
 */
/*
 * pack和unpack的第一个参数是一个格式化字符串，用来指定类型/字节序以及要存储的数值的数目。
 * S4中的S表示unsigned short、机器字节序，4表示有四个数值要存储，4可以用*来替代，表示任意个数值要被存储。
 * unpack是pack的逆过程，返回一个数组。
 */
$unpacked=unpack('S4num',$packed);//在unpack的格式化字符串中可以追加键名。
echo '<pre>';
print_r($unpacked);
echo '</pre>';
$nums = unpack('S1a/S1b/S1c/S1d',$packed);//多个格式化字符序列必须用斜杠来分割。
echo '<pre>';
print_r($nums);
echo '</pre>';
//可以使用unpack来进行数据类型转换。
$s = 'platypus';
$ascii = unpack('c*',$s);
echo '<pre>';
print_r($ascii);
echo '</pre>';
//--------------------------------------去除字符串两端的空白----------------------------------------
//可以使用ltrim( )、rtrim( )或trim( )来去掉字符串两端的空白，这能够节省存储空间并保证数据内容更加精确。
//这里所说的空白指的是：换行、回车、空白、制表符、null。
/*
 * trim()也可以去除两端处用户指定的字符，将被去除的字符要作为第二个参数传入函数，使用两个英文句点来连接两个字符以表示范围。
 */
print ltrim('10 PRINT A$',' 0..9');
echo '<br />';
print rtrim('SELECT * FROM turtles;',';');
echo '<br />';
//--------------------------------------在字符串中包含函数或表达式的计算结果----------------------------------------
$fruits = array('red'=>'Apples', 'blue'=>'Bananas', 'green'=>'Cantaloupes', 'orange'=>'Dates');
//正常引用关联数组元素的方法是：
print 'You owe '.$fruits['green'].' immediately';
echo '<br />';
//你可以直接把变量、对象属性以及数组元素（下标未被引号包裹）放在双引号字符串中。
print "You owe $fruits[green] immediately."; //效果等同于上一句。
/*
 * 在双引号字符串中插入变量会对被插入部分的语法有一些限制，比如上面对数组元素的引用就不能对下标使用引号了。
 * 使用花括号来包裹更加复杂的表达式以把它们插入到字符串中。
 */
echo '<br />';
print "You owe {$fruits['green']} immediately."; //当使用了花括号之后，就可以以正常的语法来引用变量了。
echo '<br />';
//设置默认时区。
date_default_timezone_set('UTC');
/*
 * 直接变量插入或者使用字符串连接符也可以作用在heredoc上，不过连接符和heredoc的结束标记不能在同一行。
 */
print <<< HEREDOC1
Right now, the time is {$fruits['blue']}
HEREDOC1
    . strftime('%c') . <<< HEREDOC2
 but tomorrow it will be
HEREDOC2
    . strftime('%c',time() + 86400);
//--------------------------------------生成由逗号分割的数据----------------------------------------
//你可能想把数据格式化为逗号分隔的值CSV（comma-separated values）以便把它们保存在电子表格或者数据中。
//使用fputcsv( )函数由数组数据生成CSV格式的行。
//下面的代码把数组数据写入到一个文件中。
$sales = array( array('Northeast','2016-01-01','2016-02-01',77.54),
    array('Northwest','2016-01-01','2016-02-01',436.33),
    array('Southeast','2016-01-01','2016-02-01',931.26),
    array('Southwest','2016-01-01','2016-02-01',45.21),
    array('All Regions','--','--',1297.34) );
$fh = fopen('sales.csv','w') or die("Can't open sales.csv");
foreach ($sales as $sales_line) {
    if (fputcsv($fh, $sales_line) === false) {
        die("Can't write CSV line");
    }
}
fclose($fh) or die("Can't close sales.csv");
//如果要打印CSV数据而不是写入文件，则应该使用输出流php://output。
$sales = array( array('Northeast','2016-01-01','2016-02-01',77.54),
    array('Northwest','2016-01-01','2016-02-01',436.33),
    array('Southeast','2016-01-01','2016-02-01',931.26),
    array('Southwest','2016-01-01','2016-02-01',45.21),
    array('All Regions','--','--',1297.34) );
echo '<pre>';
$fh = fopen('php://output','w');
foreach ($sales as $sales_line) {
    if (fputcsv($fh, $sales_line) === false) {
        die("Can't write CSV line");
    }
}
fclose($fh);
echo '</pre>';
//把CSV数据放入字符串中，而不是写到文件里。
$sales = array( array('Northeast','2005-01-01','2005-02-01',12.54),
    array('Northwest','2005-01-01','2005-02-01',546.33),
    array('Southeast','2005-01-01','2005-02-01',93.26),
    array('Southwest','2005-01-01','2005-02-01',945.21),
    array('All Regions','--','--',1597.34) );
ob_start();
$fh = fopen('php://output','w') or die("Can't open php://output");
foreach ($sales as $sales_line) {
    if (fputcsv($fh, $sales_line) === false) {
        die("Can't write CSV line");
    }
}
fclose($fh) or die("Can't close php://output");
$output = ob_get_contents();   //$output得到字符串
ob_end_clean();
print_r($output);
echo '<br />';
//---------------------------------------解析CSV数据------------------------------------------------
//如果CSV数据在一个文件中，则用fopen( )打开文件并用fgetcsv( )来读取数据。
$fp = fopen('sales.csv','r') or die("can't open file");
print "<table>\n";
while($csv_line = fgetcsv($fp)) {
    print '<tr>';
    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
        print '<td>'.htmlentities($csv_line[$i]).'</td>';
    }
    print "</tr>";
}
print '</table>';
fclose($fp) or die("can't close file");
//---------------------------------------生成固定宽度字段的数据记录------------------------------------------------
//你需要格式化数据记录以便每个字段占据固定数量的字符。
//使用pack()函数的格式化字符串来指定以空格填充的固定宽度字符串。
$books = array( array('Elmer Gantry', 'Sinclair Lewis', 1927),
    array('The Scarlatti Inheritance','Robert Ludlum',1971),
    array('The Parsifal Mosaic','William Styron',1979) );
echo '<pre>';
foreach ($books as $book) {
    print pack('A27A15A4', $book[0], $book[1], $book[2]) . "\n";  //A表示以空格填充，25表示填充后的字符串长度.
}
echo '</pre>';
//下面的代码不使用空格而使用英文句点来补足长度，使用substr来保证字符串不能太长，使用str_pad来补足长度。
echo '<pre>';
foreach ($books as $book) {
    $title  = str_pad(substr($book[0], 0, 25), 27, '.');
    $author = str_pad(substr($book[1], 0, 15), 18, '.');
    $year   = str_pad(substr($book[2], 0, 4), 4, '.');
    print "$title$author$year\n";
}
echo '</pre>';
//---------------------------------------将字符串中的文本按照指定长度换行------------------------------------------------
$s = "Four score and seven years ago our fathers brought forth on this continent a new nation, conceived in liberty and dedicated to the proposition that all men are created equal.";
print "<pre>\n".wordwrap($s)."\n</pre>";
//wordwrap()将文本换行，默认达到换行条件的字符数量是75，可以通过该函数的第二个参数来指定其它值。
echo '<pre>';
echo wordwrap($s,50);
echo '</pre>';
//注意wordwrap()要在<pre></pre>标签中才能在页面上看到效果。
//使用第三个参数来设定如何包裹分割出的字符串部分。
echo '<pre>';
echo wordwrap($s,50,"\n\n");
echo '</pre>';
//本质上wordwrap()函数是将字符串用给定的数值进行分块，并将指定的内容插在两个块之间。
echo '<pre>';
print wordwrap('jabber wocky',5);
echo '<br />';
print wordwrap('jabber wocky',5,"\n",1);
echo '</pre>';
//该函数还有第四个参数，用来处理一个单词超过分块长度的情形。如果值为1，那么这个单词会被割裂开来，否则，分割位于该单词之后。
//---------------------------------------字符串分解------------------------------------------------
//使用explode()函数来分割常量字符串，而第一个参数所表达的分隔符也是常量。
$words = explode(' ','My sentence is not very complicated');
echo '<pre>';
print_r($words);
echo '</pre>';
$dwarves = 'dopey,sleepy,happy,grumpy,sneezy,bashful,doc';
//第三个参数表示分块的极限，如果实际分块的数目大于这个极限，那么最后一块包含所有剩余内容。
$dwarf_array = explode(',',$dwarves,5);
echo '<pre>';
print_r($dwarf_array);
echo '</pre>';
//---------------------------------------使用substr_replace插入或删除字符-----------------------------------------
$str = 'Hello,i love PHP!';
// 使用substr_replace在某索引处插入字符。
echo substr_replace($str, 'you and ', 6, 0);
echo '<br />';
// 使用substr_replace删除某索引处的字符。
echo substr_replace($str, '', 0, 1);
// 另外，可以直接给某索引处字符赋值来删除字符。
$str{3} = '';
echo '<br />';
echo $str;
?>