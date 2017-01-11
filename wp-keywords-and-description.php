<?php
/*
 Plugin Name: keywords description
 Plugin URI: http://www.wptoutiao.com/
 Description: add keywords and description into head
 Version: 1.0.0
 Author: shenglei
 Author URI: http://www.wptoutiao.com/
 */
if( is_admin() ) {
	/*  利用 admin_menu 钩子，添加菜单 */
	add_action('admin_menu','add_diy_menu');
}

function add_diy_menu()
{
	//权限 Administrator Editor Author Contributor Subscriber
	add_options_page("keywords&description","keywords&description", 8, __FILE__, 'my_function_menu');
}

function my_function_menu()
{
	$keywords = get_option("keywords");
	$description = get_option("description");
	if(isset($_POST['action']) && $_POST['action'] == 'Save')
	{
		$keywords = $_POST['keywords'];
		$description = $_POST['description'];
		
		update_option("keywords", $keywords);
		update_option("description", $description);
	}
	
    echo "<h2>欢迎进入keywords&description后台</h2>";
	?>
	<form method="post" action="<?php__FILE__?>">
		<p>
			关键词：
			<br />
			<textarea name="keywords" cols="60" rows="1"><?php echo $keywords;?></textarea>
			<br />
			描述  ：
			<br />
			<textarea name="description" cols="60" rows="10"><?php echo $description;?></textarea>
		</p>
		
		<p>
			<input type="submit" name="action" value="Save" class="button-primary" />
		</p>
		<p>
			建议：不要经常修改keywords和description。
		</p>
	</form>
	<?php
}


 
function echo_keywords_description() {
	if (is_home()){

		$keywords = get_option("keywords");
        	$description = get_option("description");
	} elseif (is_single() || is_page()){

		$description1 = get_post_meta(get_the_ID(), "description", true);
$content = get_the_content();
		$description2 = mb_strimwidth(strip_tags(apply_filters('the_content', $content)), 0, 200, "…");
	echo $description2;
		$description = $description1? $description1 : $description2;
		$keywords = get_post_meta($post->ID, "keywords", true);

		if($keywords == '') {
			$tags = wp_get_post_tags($post->ID);    
			foreach ($tags as $tag ){
				$keywords = $keywords . $tag->name . ", ";
			}
			$keywords = rtrim($keywords, ', ');
		}
		if($keywords == '')
		{
			$keywords = single_post_title('', false);
		}
	} elseif (is_category()){
		$keywords = single_cat_title('', false);
		$description = category_description();
	} elseif (is_tag()){
		$description = tag_description();
		$keywords = single_tag_title('', false);
	}
	else{
		$keywords = single_cat_title('', false);
		$description = category_description();
	}
	$description = trim(strip_tags($description));
	$keywords = trim(strip_tags($keywords));

	echo '<meta name="keywords" content="'.$keywords.'" />'."\n";
	echo '<meta name="description" content="'.$description.'" />'."\n";
}
add_action('wp_head', 'echo_keywords_description', 0);