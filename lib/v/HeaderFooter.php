<?php
class v_HeaderFooter{
	/**
	 * list of javascript files to include
	 * @var array
	 */
	private $scripts = array();

	public function __construct(){
		$this->scripts[] = 'lib/v/base.js';
	}

	public function getHeader($head_params=null){
		ob_start();
		?>
			<html>
				<head>
					<title><?php echo $head_params['title']; ?></title>
					<link rel="stylesheet" href="<?php echo auto_version('lib/v/base.css'); ?>" type="text/css" />
					<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
					<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
				</head>
				<body>
		<?php
		return ob_get_clean();
	}

	function yell(){
		echo "i AM ALIVE";
	}
	
	public function addScript($filepath){
		$this->scripts[] = $filepath;
	}

	public function getFooter($foot_params=null){
		ob_start();
		foreach($this->scripts as $script){
			echo "<script src='".auto_version($script)."' type='text/javascript'></script>";
		}
		?>
				
			</body>
			</html>
		<?php
		return ob_get_clean();
	}
	
}
?>
