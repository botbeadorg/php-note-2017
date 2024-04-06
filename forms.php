<?php
// 浏览器本质上也是一个客户端，浏览器与服务器之间还是C/S模式。
// PHP的过人之处是它将form变量无缝地集成到你的程序当中。
// 它使得Web编程平滑而且简单，加快了从Web form到PHP代码再到HTML输出的过程。
// 无论PHP何时处理页面，它都会检查URL、表单变量、上传的文件、适用的cookie，Web服务器以及环境变量。
// 这些内容在下列数组中都可以被直接访问到：$_GET、$_POST、$_FILES、$_COOKIE、$_SERVER和$_ENV。
// 它们分别保存着设置在查询字符串中、POST请求体中、上传的文件中、cookie中、Web服务器中以及服务器运行环境中的所有变量。
// 另外，还有一个巨大的数组$_REQUEST，它包含着来自上述六数组中的所有值。
// PHP会按照variables_order配置指令指定的顺序依次把上述六数组中的键/值对存储在$_REQUEST中，
// 这个顺序一般为EGPCS或者GPCS，注意每个字母都是上述数组名的首字母。
// 在添加$_REQUEST数组元素的工程中，如果不同数组中存在相同键名，那么已经存在的键的值会被覆盖。
?>
<?php
// 尽管GET和POST是两次请求，但由于$slct_ary是全局且它本身不会发生变化，所以在GET和POST中都可用。
$chk_value_given = 'something';
$slct_ary = array('eggs' => 'Eggs Benedict',
	'toast' => 'Buttered Toast with Jam',
	'coffee' => 'Piping Hot Coffee');
// GET方法——当你在浏览器地址栏输入一个URL或者你点击了一个超链接时，浏览器发出GET请求，
// 这意味着向服务器请求一些服务器端拥有的东西。
if ('GET' === $_SERVER['REQUEST_METHOD']) { 
	create_multipage_form();
?>
<br />
<!--
	form的action指明当请求到达服务器时，由哪个PHP脚本处理这个请求。
	下面这个PHP代码中使用echo是因为要将PHP生成的HTML代码输出（插入）到HTML文档中，这一点很重要。
	另外PHP脚本中使用了$_SERVER['SCRIPT_NAME']，这正是GET请求所要求的脚本文件，
	然后使用这同一脚本再去处理POST请求。
	反过来说，如果不需要将PHP产生的结果插入到HTML文档，就不需要echo。
-->
<form action="<?php echo htmlentities($_SERVER['SCRIPT_NAME']) ?>" method="POST"
	  enctype="multipart/form-data">
What is your first name?
<br />
<!--input的name和在input中输入的数据将作为键/值对添加到服务器的$_POST数组当中-->
<!--下面这个input标记，将会在服务端的$_POST数组中产生一个$_POST['first_name']-->
<input type="text" name="first_name" />
<br /><br />
How old are you?<br />
<input type="text" name="age" />
<br /><!--br是break row，打破一行，换行的意思-->
<br />
What's your email address?<br />
<input type="text" name="email" />
<br /><br />
What is your favorite food(DROPDOWNLIST)?
<br />
<?php generate_drop_down($slct_ary, true); ?>
<br /><br />
What is your the most favorite food(RADIOBUTTON)?
<br />
<?php generate_radio_btns($slct_ary, 'toast'); ?>
<br /><br />
What is your favorite foods(CHECKBOXES)?
<br />
<!--生成复选框的时候传入的是以[]结尾的名字-->
<?php generate_checkboxes($slct_ary, 'foods[]', 'eggs'); ?>
<br /><br />
<input type="file" name="document"/>
<br /><br />
<!--作为一个form，必须要有type="submit"的input标签，这个input用来使浏览器向服务器发生请求，而请求的类型由form的method指定-->
<input type="submit" value="Say Hello" />
</form>
<?php
// POST方法——当你提交一个method属性被设置为POST的表单时，浏览器发出POST请求，
// 这意味着向服务器提交一些浏览器端的东西。
// 服务器端的$_SERVER['REQUEST_METHOD']存储的值一定是大写的GET或POST。
} elseif ('POST' === $_SERVER['REQUEST_METHOD']) {
	// filter_has_var相当于array_key_exists()，检测一个键是否存在。
	// filter_input能够获取一个键经过过滤后的值，过滤策略为第三个参数，
	// 这里FILTER_SANITIZE_STRING会剥离HTML标签，过滤掉非ASCII码的部分，并对与号(&)进行编码。
	// filter_input过滤成功的话，会返回一个可用的值，否则会返回false。
	if(!(filter_has_var(INPUT_POST, 'first_name') && 
		strlen(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING))>=5)) 
	// INPUT_POST对应$_POST数组
	// INPUT_GET对应$_GET数组，INPUT_COOKIE、 INPUT_SERVER、INPUT_ENV依次类推。
	{
		echo 'Inavlidate input\'s name, or the input\'s value is shorter than the expected length.';

	}else{
?>
	Hello, <?php echo escape_something($_POST['first_name'])?> !
<?php
	}
	$age = -1;
	// 注意HTML代码和PHP脚本混杂在一起的方式，注意不同的PHP代码片段要保证整体PHP代码的完整性。
	if(verif_number_float($age, 'age', INPUT_POST)){
		echo '<br />';
		echo 'Your ages is '.$age;
	}else{
		echo '<br />';
		echo 'please enter a correct age!';
	}
	$email = '';
	if(verif_email($email, 'email', INPUT_POST)){
		echo '<br />';
		echo 'Your email address is '.$email;
		echo '<br />';
	}else{
		echo '<br />';
		echo 'please enter a correct email address';
	}
	// 使用array_key_exists和filter_has_var的目的一样，避免被恶意插入不存在的值。
	if (! array_key_exists($_POST['food'], $slct_ary)) {
		echo "You must select a valid choice.";
	}
	if(verif_radio_input($r_v, 'food1')){
		echo 'Your choise is '.$r_v.'<br />';
	}else{
		echo 'Your choise is not valid.'."<br />";
	}
	// 检测checkbox输入的时候，传入的是不带[]的名字。
	verif_checkbox_input('foods');
	process_file('document');
	echo '<pre>';
	print_r($_FILES);
	print_r($_POST);
}
function verif_age(&$age, $key, $INPUT_X = INPUT_POST)
{
	// FILTER_VALIDATE_INT验证是否是一个有效的整数，若验证成功则返回一个整数。
	// FILTER_VALIDATE_FLOAT验证是否是一个有效的浮点数，若验证成功则返回一个浮点数。
	if(filter_has_var($INPUT_X, $key) && 
		($age = filter_input($INPUT_X, $key, FILTER_VALIDATE_INT)) && 
		(256 >$age && $age >= 0))
	{
		return true;
	}else{
		return false;
	}
}
function verif_email(&$email, $key, $INPUT_X = INPUT_POST)
{
	// 使用FILTER_VALIDATE_EMAIL验证电子邮箱格式是否正确，但它不能验证邮箱是否有效。
	if(filter_has_var($INPUT_X, $key) && 
		($email = filter_input($INPUT_X, $key, FILTER_VALIDATE_EMAIL))
		){
		return true;
	}else{
		return false;
	}
}
function verif_number_8(&$number, $key, $INPUT_X = INPUT_POST)
{
	// 使用第四个参数（验证标志）FILTER_FLAG_ALLOW_OCTAL验证八进制数字输入，返回值仍是十进制。
	// 输入数字以前置0开始，如果某数位值大于七则验证失败。
	if(filter_has_var($INPUT_X, $key) && 
		($number = filter_input($INPUT_X, $key, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL)))
	{
		return true;
	}else{
		return false;
	}
}
function verif_number_16(&$number, $key, $INPUT_X = INPUT_POST)
{
	// 使用第四个参数（验证标志）FILTER_FLAG_ALLOW_HEX验证十六进制数字输入，返回值仍是十进制。
	// 输入数字以前置0x开始，如果某数位值大于f则验证失败。
	// 另外，在使用16进制和8进制验证时，如果没有前置的0x或0，则以十进制进行验证。
	if(filter_has_var($INPUT_X, $key) && 
		($number = filter_input($INPUT_X, $key, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_HEX)))
	{
		return true;
	}else{
		return false;
	}
}
function verif_number_float(&$number, $key, $INPUT_X = INPUT_POST)
{
	// 使用第四个参数（验证标志）FILTER_FLAG_ALLOW_THOUSAND验证带千分位分割符的浮点数，返回值仍是十进制。
	// 浮点数验证还可以验证十进制数，包括带千分位分割符的十进制数。
	// 但FILTER_FLAG_ALLOW_THOUSAND只适用于FILTER_VALIDATE_FLOAT。
	if(filter_has_var($INPUT_X, $key) && 
		($number = filter_input($INPUT_X, $key, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND)))
	{
		return true;
	}else{
		return false;
	}
}
// 使用<select/>创建下拉列表。
function generate_drop_down($slct_ary, $ary_type)
{
	// 打印select头标签。
	echo "<select name='food'>\n";

	if($ary_type){
		// 打印列表中的选项。实际上数组元素的键和值，谁做选项的显示字符串，谁做选项的值完全看echo时它们在标签中插入的位置。
		// 这里用数组的键来做option的值，是为了在POST当中使用array_key_exists。
		foreach ($slct_ary as $posted_value => $item_display) {
			echo "<option value='$posted_value'>$item_display</option>\n";
		}
	}else{
		foreach ($slct_ary as $posted_value) {
			// 注意，如果没有设置value，则用option显示的内容来做name对应的值。
			echo "<option>$posted_value</option>\n";
		}
	}
	
	// 打印select尾标签。
	echo "</select>";
	// 在POST请求中，select标记返回的键值对的键是select的name，值是某个option的value。
}
// 创建单选框
function generate_radio_btns($slct_ary, $dft_value = "")
{
	if(isset($slct_ary) && !empty($slct_ary)){
		if(empty($dft_value)){
			foreach ($slct_ary as $key => $choice) {
				// 一组单选按钮使用相同的名字
				echo "<input type='radio' name='food1' value='$key'/> $choice \n";
			}
		}else{
			foreach ($slct_ary as $key => $choice) {
				echo "<input type='radio' name='food1' value='$key'";
				if ($key === $dft_value) {
					// 设置默认选项
					echo ' checked="checked"';
				}
				echo "/> $choice \n";
			}
		}
	}
}
function verif_radio_input(&$radio_value, $radio_name, $INPUT_X = INPUT_POST)
{
	global $slct_ary;
	if(filter_has_var($INPUT_X, $radio_name)){
		if(array_key_exists($_POST[$radio_name], $slct_ary)){
			$radio_value = $slct_ary[$_POST[$radio_name]];
			return true;
		}else{
			$radio_value = '';
			return false;
		}
	}
}
/*
 * 一个变量被设置意味着它具有这样的形式：$varX = everything but null;。
 * 那么一个变量未被设置可能是四种情况：
 * 1、该变量在脚本中根本不存在；
 * 2、该变量在脚本中存在，但是未被赋值；
 * 3、该变量被赋值为null；
 * 4、该变量经unset()函数处理。
 *
 * 在PHP中，每个变量的值都可以转变为布尔值true或false。
 * 值可被转为false的变量称之为空变量，否则，如果能被转为true就是非空变量。
 * 所有未被设置的变量都是空变量。
 * 反过来说，所有非空变量都是被设置的变量。
 */
function generate_checkboxes(array $slct_ary, $group_name, $dft_value = "")
{
	if(!empty($slct_ary) && !empty($group_name)){
		if($dft_value == false){
			foreach ($slct_ary as $key => $choice) {
				// 为了让PHP能够正确地处理多个checkbox的值，这些checkbox的名字必须以[]结尾；
				// 具有相同的名字的checkbox上传的数据被组织在同一个数组中。
				// 比如有一组checkbox的名字都是foods[]，那么$_POST['foods']就是一个数组，
				// 该数组中保存着这组checkbox的值。
				// 如果我们只需要一个checkbox，那么这个checkbox的名字就不需要末尾的[]了。
				echo "<input type='checkbox' name='$group_name' value='$key'/> $choice \n";
			}
		}else{
			foreach ($slct_ary as $key => $choice) {
				echo "<input type='checkbox' name='$group_name' value='$key'";
				if ($key === $dft_value) {
					// 设置默认选项
					echo ' checked="checked"';
				}
				echo "/> $choice \n";
			}
		}
	}
}
/*
 * 在留空的时候，不同类型的表单元素会在POST/GET数据中产生不同的结果。
 * 空的文本框，文本域以及文件上传域会产生长度为零的字符串；
 * 未被选中的checkbox以及radio button不会在GET/POST数组中产生对应的数据。
 * 在留空的时候，通常浏览器会对只允许单选的下拉菜单强制做出选择，
 * 但是可允许多选的下拉菜单则表现的像checkbox一样——不会在GET/POST数组中产生对应的数据。
 */
function verif_checkbox_input($ckbox_name)
{
	global $chk_value_given;
	global $slct_ary;
	if(!empty($ckbox_name) && filter_has_var(INPUT_POST, $ckbox_name)){
		if(filter_input(INPUT_POST, $ckbox_name, FILTER_DEFAULT,FILTER_REQUIRE_ARRAY)){
			// array_keys返回一个数组，该数组的内容是参数（数组）的所有的键（或键的子集）。
			// array_intersect是计算两个集合的交集。
			if (array_intersect($_POST[$ckbox_name], array_keys($slct_ary)) != $_POST[$ckbox_name]) {
				echo "You must select only valid choices.";
			}else{
				print_r($_POST[$ckbox_name]);
			}
		}else{
			$chk_value_posted = null;
			if ($_POST[$ckbox_name] == $chk_value_given) {
				$chk_value_posted = true;
			} else {
				$chk_value_posted = false;
				print 'Invalid checkbox value submitted.';
			}
			if ($chk_value_posted) {
				echo '<pre>';
				echo $_POST[$ckbox_name];
			}
		}
	}
}
/*
 * 你希望安全地显示用户在HTML页面上输入的内容。
 * 例如，你希望用户能过对帖子添加评论但不希望评论中的HTML或Javascript代码会引起问题。
 * htmlentities()函数在htmlspecialchars()的基础上加以扩展来对任何拥有实体的字符加以编码。
 *
 * 有些字符构成了HTML的语法，比如大于号>和小于号<，那么实际内容中的>，<就会与构成语法的>、<混淆；
 * 为了避免混淆，HTML设计了独特的字符序列来表达实际内容中的这类特殊字符；
 * 那么这种字符序列就是相应的字符的实体。
 */
function escape_something($something_posted)
{
	return htmlentities($something_posted);
}

/*
 * 上传的文件信息保存在超全局数组$_FILES中。
 * 对于表单中的每个文件类型的input标签，都会在$_FILES中创建一个以该元素名为键的对应元素，这个元素是一个数组。
 * 比如表单中有一个<input type="file" name="document"/>，那么表单提交后在$_FILES中就会有一个$_FILES['document']，
 * 而这个$_FILES['document']元素实就是一个数组。
 *
 * 每个$_FILES的元素作为数组，它自己包含了五个元素：
 * name——上传文件的名字，它可能是一个全路径名也可能是一个文件名，这由浏览器来提供；
 * type——MIME类型，这由浏览器提供；
 * size——文件大小，以字节计算，由服务器计算出来；
 * tmp_name——文件在服务器上临时保存的位置；
 * error——文件上传出错的错误码。
 * 最常用的错误码为UPLOAD_ERR_OK (0)，表达上传成功，无错误。
 *
 * 如果在$_FILES中没有看到上传文件的信息，那么保证要在form的开标签中加入enctype="multipart/form-data"。
 */
function process_file($file_elem_name)
{
	// 如果$_FILES中相关元素被设置，并且上传中没有错误。
	if (isset($_FILES[$file_elem_name]) &&
		(UPLOAD_ERR_OK == $_FILES[$file_elem_name]['error'])){
		if(empty($_FILES[$file_elem_name]['tmp_name']) ||
			!is_uploaded_file($_FILES[$file_elem_name]['tmp_name']))return;
		$newPath = './' . basename($_FILES[$file_elem_name]['name']);
		if (move_uploaded_file($_FILES[$file_elem_name]['tmp_name'], $newPath)) {
			echo "File saved in $newPath";
		}else{
			echo "Couldn't move file to $newPath";
		}
	}else{
		echo "No valid file uploaded.";
	}
}
/*
 * 这个函数的关键逻辑：
 * 1、不同的form使用相同名字的脚本来处理，注意，尽管脚本名字相同，但实际上却有部分不同；
 * 2、在服务器向客户端发送HTML（form）的时候，设置好客户端向服务器发送的值（POST的值）；
 *    然后基于这个POST的值，再次发送HTML（form）... ...。
 * 3、这种从服务器发送到客户端，然后从客户端发回到服务器的方式类似于COOKIE。
 */
function create_multipage_form()
{
	/*
	 * session中的所有数据都保存在服务器端。
	 * 这使得每个请求都比较小——无需重复提交在前面步骤中输入的数据——并且减少了验证，
	 * 你只需要验证每次提交的数据片段即可。
	 */
	$stage="<?php
session_start();
if ((\$_SERVER['REQUEST_METHOD'] == 'GET') || (! isset(\$_POST['stage']))) {
	\$stage = 1;
} else {
	\$stage = (int) \$_POST['stage'];
}
\$stage = max(\$stage, 1);
\$stage = min(\$stage, 3);
if (\$stage > 1) {
	foreach (\$_POST as \$key => \$value) {
		\$_SESSION[\$key] = \$value;
	}
}
include __DIR__ . \"/stage-\$stage.php\";
?>
";
	$fh = fopen('stage.php', 'w') or die("can't create file");
	if (-1 == fwrite($fh,$stage)) { 
		die("can't write data"); 
	}
	fclose($fh) or die("can't close file");
	$stage1="<form action='<?= htmlentities(\$_SERVER['SCRIPT_NAME']) ?>' method='post'>
Name: <input type='text' name='name'/> <br/>
Age: <input type='text' name='age'/> <br/>
<input type='hidden' name='stage' value='<?= \$stage + 1 ?>'/>
<input type='submit' value='Next'/>
</form>";
	// 在form的构成中，存在一个隐藏的input，用来追踪页面的步骤。
	$fh = fopen('stage-1.php', 'w') or die("can't create file");
	if (-1 == fwrite($fh,$stage1)) { 
		die("can't write data"); 
	}
	fclose($fh) or die("can't close file");
	$stage2="<form action='<?php echo htmlentities(\$_SERVER['SCRIPT_NAME']); ?>' method='post'>
Favorite Color: <input type='text' name='color'/> <br/>
Favorite Food: <input type='text' name='food'/> <br/>
<input type='hidden' name='stage' value='<?php echo \$stage + 1; ?>'/>
<input type='submit' value='Done'/>";
	$fh = fopen('stage-2.php', 'w') or die("can't create file");
	if (-1 == fwrite($fh,$stage2)) { 
		die("can't write data"); 
	}
	fclose($fh) or die("can't close file");
	$stage3="Hello <?= htmlentities(\$_SESSION['name']) ?>.<br />
You are <?= htmlentities(\$_SESSION['age']) ?> years old.<br />
Your favorite color is <?= htmlentities(\$_SESSION['color']) ?> 
and your favorite food is <?= htmlentities(\$_SESSION['food']) ?>.";
	$fh = fopen('stage-3.php', 'w') or die("can't create file");
	if (-1 == fwrite($fh,$stage3)) { 
		die("can't write data"); 
	}
	fclose($fh) or die("can't close file");
	echo '<a href="stage.php">MultiPage Form</a>'.'<br />';
}
?>