<?
namespace LightAdmin\Image\Controllers;

use abeautifulsite\SimpleImage;

class SimpleImageController extends SimpleImage
{

	/**
	 * @return string
	 */

	public $autopath = true;
	public $quality = 100;


	public function make($img){
		return $this->load($img);
	}

	function save($filename = null, $quality = null, $format = null) {

		// create puth is not exist
		if($this->autopath==true  and $filename){
			$this->create_puth($filename);
		}

		// Determine quality, filename, and format
		$quality = $quality ?: $this->quality;
		$filename = $filename ?: $this->filename;
		if( !$format ) {
			$format = $this->file_ext($filename) ?: $this->original_info['format'];
		}
		// Create the image
		switch (strtolower($format)) {
			case 'gif':
				$result = imagegif($this->image, $filename);
				break;
			case 'jpg':
			case 'jpeg':
				imageinterlace($this->image, true);
				$result = imagejpeg($this->image, $filename, round($quality));
				break;
			case 'png':
				$result = imagepng($this->image, $filename, round(9 * $quality / 100));
				break;
			default:
				throw new \Exception('Unsupported format');
		}
		if (!$result) {
			throw new \Exception('Unable to save image: ' . $filename);
		}
		return $this;
	}

	public function crop($width, $height=false, $x2=false, $y2=false) {

// если указан только один параметр, значит, это квадратная обрезка:
		if(!$height){
			$this->square_crop($width);
			return $this;
		}
		$src_x = 0; /////// ???
		$src_y = 0; /////// ???


		$src_w = $this->get_width();
		$src_h = $this->get_height();

// если вдруг генерируемое изображение больше исходного, создаем новое соотношение
		if($width>$src_w or $height>$src_h){
			if($width>=$height){
				$height = $height/$width*$src_w;
				$width = $src_w;

			}else{
				$width = $width/$height*$src_h;
				$height = $src_h;
			}
		}

		$dst_h = ($src_h*$width)/$src_w;
		$dst_w = $width;
		// вычисляем отступы
		$dst_x = 0;
		$dst_y = -($dst_h-$height)/2;

// если оказалось, что высота образовавшейся области больше, нежели нашего изображения, тогда меняем стиль генерации
		if($height>$dst_h){
			$dst_w = $src_w/$src_h*$height;
			$dst_h = $height;
			$dst_y = 0;
			$dst_x = -($dst_w-$width)/2;
		}

		$new = imagecreatetruecolor($width, $height);
		imagealphablending($new, false);
		imagesavealpha($new, true);
		imagecopyresampled($new, $this->image, $dst_x, $dst_y ,$src_x ,$src_y ,$dst_w, $dst_h, $src_w, $src_h);

		$this->width = $width;
		$this->height = $height;
		$this->image = $new;

		return $this;

	}

	public function create_puth($img){

		$dir = pathinfo($img)['dirname'];

		if(is_dir($dir)) return true;

		$path = '';
		foreach (explode("/",$dir) as $dir){
			$path .= $dir."/";

			if(!is_dir($path)) mkdir($path);

		}

	}

}
?>