<?php
// 使用function_exists检测imagecreate函数是否存在，以此判断GD是否安装成功；
// 通过phpinfo()也可以查看GD是否安装，如果未安装则需要在php.ini中打开php_gd2.dll扩展。
if(TRUE === function_exists('imagecreate')){
	echo 'GD Library is installed!';
}
echo '<br />';
// 检查GD库支持的图片格式。
$i_types = imagetypes();
if($i_types & IMG_JPG){
	echo 'GD Library supports JPG format.';
}
echo '<br />';
if($i_types & IMG_PNG){
	echo 'GD Library supports PNG format.';
}
echo '<br />';
if($i_types & IMG_GIF){
	echo 'GD Library supports GIF format.';
}
function draw_the_first_pic(&$pic_name)
{
	$width = 200;
	$height = 50;
	// 创建画布
	$canvas = ImageCreateTrueColor($width, $height);
	$background_color = 0xCCCCCC;
	// 0,0——左上顶点坐标
	// 200-1,50-1——右下顶点坐标
	ImageFilledRectangle($canvas, 0, 0, 200 - 1, 50 - 1, $background_color);
	$foreground_color = 0xFFFFFF;
	ImageFilledRectangle($canvas, 50, 10, 150, 40, $foreground_color);
	// 发送Content-type头的目的是让浏览器知道你要发送哪种类型的图片。
	// header('Content-type: image/png');
	// 为了把图片写到磁盘上而不是直接发送到浏览器中，用第二个参数指定文件名（及路径）。
	// 由于没打算向浏览器发送图片，那么也就不需要调用header了。
	// 向磁盘写文件时要保证PHP有足够的写权限。
	ImagePNG($canvas, $pic_name = 'the_first_pic.png');
	// 当脚本结束时，PHP会清除画布占据的资源（内存），
	// 也可以用ImageDestroy手动释放内存。
	ImageDestroy($canvas);
}
draw_the_first_pic($pic_name1);
echo '<br />'."<img src = '$pic_name1' />";
function draw_characters_with_buildin_font(&$pic_name)
{
	$canvas = ImageCreateTrueColor(200, 50);
	$text_color = 0x0;
	ImageFilledRectangle($canvas, 0, 0, 200 - 1, 50 - 1, 0xFFFFFF);
	/*
	 * 参数2——取值范围：1,2,3,4,5，字体依次增大；
	*  参数3、4——要绘制的文本在画布中的左上顶点坐标；
	*  参数5——要绘制的文本
	*  参数6——文本颜色
	 */
	ImageString($canvas, 5, 10, 10, 'I love PHP Cookbook', $text_color);
	ImagePNG($canvas, $pic_name = 'the_image_with_string1.png');
	ImageDestroy($canvas);
}
draw_characters_with_buildin_font($pic_name1);
echo '<br />'."<img src = '$pic_name1' />";
function draw_characters_with_TrueTypeFont(&$pic_name)
{
	$size = 20;
	$angle = 0;
	$x = 20;
	$y = 35;
	$text_color = 0x3976ae;
	$text = 'I Love you,mom!';
	$fontpath = 'ravie.ttf';
	$canvas = ImageCreateTrueColor(350, 50);
	ImageFilledRectangle($canvas, 0, 0, 350 - 1, 50 - 1, 0xFFFFFF);
	// imagefttext中的两个坐标定义的是第一个字符的基点（大约是该字符的左下角）。
	ImageFTText($canvas, $size, $angle, $x, $y, $text_color, $fontpath, $text);
	ImagePNG($canvas, $pic_name = 'the_image_with_ttf.png');
	ImageDestroy($canvas);
}
draw_characters_with_TrueTypeFont($pic_name1);
echo '<br />'."<img src = '$pic_name1' />";
function test_imageftbbox()
{
	// ftb——free type bounding
	// bounding box——包围盒子，边界盒子，这个盒子很明显在文本的外围。
	$ary = imageftbbox(20,0,'ravie.ttf','g love you,mom',array());
	// imageftbbox返回一个包含4对（8个）值的数组，
	// 前两个是左下角的坐标，然后沿逆时针方向包含其它三个角的坐标。
	// 这些坐标是相对于文本基线的，向右向下为正方向。
	echo '<pre>';
	print_r($ary);
}
test_imageftbbox();
function draw_watermark_with_ImageCopyMerge(&$pic_name)
{
	$image = imagecreatefromjpeg('bg.jpg');
	// 从现存的png或jpeg图像创建画布，画布中包含图像的所有数据。
	$stamp = imagecreatefrompng('girl_avatar.png');
	$margin = ['right' => 10, 'bottom' => 10];
	$opacity = 50; // between 0 and 100%
	ImageCopyMerge($image, $stamp,
		imagesx($image) - imagesx($stamp) - $margin['right'],   // 确定从$image的何处开始绘制$stamp。
		imagesy($image) - imagesy($stamp) - $margin['bottom'],
		0, 0, imagesx($stamp), imagesy($stamp),
		$opacity);
	Imagepng($image, $pic_name = 'draw_watermark_with_ImageCopyMerge.png');
	imagedestroy($image);
	imagedestroy($stamp);
}
draw_watermark_with_ImageCopyMerge($pic_name1);
echo '<br />'."<img src = '$pic_name1' width ='270px' height = '480px'/>";
// 保持原图长宽比的缩放。
function scale_down_image_resampled()
{
	$filename = __DIR__ . '/cat.png';
	$scale = 0.5; // Scale
	$image = ImageCreateFromPNG($filename);
	$thumbnail = ImageCreateTrueColor(ImageSX($image) * $scale, ImageSY($image) * $scale);
	ImageColorTransparent($thumbnail, ImageColorAllocateAlpha($thumbnail, 0, 0, 0, 127));
	ImageAlphaBlending($thumbnail, false);
	ImageSaveAlpha($thumbnail, true);
	ImageCopyResampled($thumbnail, $image, 0, 0, 0, 0,
		ImageSX($thumbnail), ImageSY($thumbnail), ImageSX($image), ImageSY($image));
	ImagePNG($thumbnail, 'cat_resampled.png');
	echo '<br />'."<img src = 'cat_resampled.png' />";
	ImageDestroy($image);
	ImageDestroy($thumbnail);
}
scale_down_image_resampled();
function scale_down_image_with_fixed_size()
{
	$filename = __DIR__ . '/cat.png';
	$w = 200; 
	$h = 80;
	$original = ImageCreateFromPNG($filename);
	$thumbnail = ImageCreateTrueColor($w, $h);
	ImageColorTransparent($thumbnail, ImageColorAllocateAlpha($thumbnail, 0, 0, 0, 127));
	ImageAlphaBlending($thumbnail, false);
	ImageSaveAlpha($thumbnail, true);
	
	$x = ImageSX($original);
	$y = ImageSY($original);
	$scale = min($x/$w, $y/$h);
	//使用min()函数的目的是将被缩放的图像区域维持在原图的内部。
	
	ImageCopyResampled($thumbnail, $original, 0, 0, ($x - ($w * $scale)) / 2, ($y - ($h * $scale)) / 2, 
		$w, $h, $w * $scale, $h * $scale);
	ImagePNG($thumbnail, 'cat_fixed_size.png');
	echo '<br />'."<img src = 'cat_fixed_size.png' />";
	imagedestroy($original);
	imagedestroy($thumbnail);
}
scale_down_image_with_fixed_size();
?>