<?php
/*
Plugin Name: Multi Social Widget
Plugin URI: http://www.wpfruits.com
Description: Multi Social Widget plugin allows you to dispaly your social photo streams from various social networks (Pinterest, Dribbble, Flickr and Instagram) on your WordpPress site.
Version: 1.0.0
Author: tikendramaitry, rahulbrilliant2004, Nishant Jain, gunjan-rai
Author URI: http://www.wpfruits.com
Licence: GNU GPL Version 3
*/
/********************************************
MULTI SOCIAL WIDGET START
*********************************************/
add_action('wp_enqueue_scripts', 'msw_frontend_scripts');
function msw_frontend_scripts() {
	if(!is_admin()){
		wp_enqueue_script ('jquery');
	}
}

class WPFMultiSocialWidget extends WP_Widget {
    /** constructor */
    function WPFMultiSocialWidget() {
		$widget_ops = array('classname' => 'wpfmultisocialwidget', 'description' => 'A Widget for Multi Social Stream' );
		$this->WP_Widget('WPFMultiSocialWidget',"Multi Social Widget", $widget_ops);	
    }
    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
		global $shortname;	
        extract( $args );
		$title = esc_attr($instance['title']);
		$user_name = esc_attr($instance['user_name']);							
		$num_posts = esc_attr($instance['num_posts']);	
		$thumb_width   = (isset($instance['thumb_width']) && $instance['thumb_width'] !="") ? esc_attr($instance['thumb_width']) : '100';	
		$thumb_height  = (isset($instance['thumb_height']) && $instance['thumb_height'] !="") ? esc_attr($instance['thumb_height']) : '100';	
		
		$social_network = esc_attr($instance['social_network']);
		if(empty($num_posts)){$num_posts = 4;}
        ?>
            <?php echo $before_widget; ?>
                <?php if ( $title )
					echo $before_title . $title . $after_title; ?>
					<script>
						(function($){$.fn.extend({wpf_social_pics:function(options){var defaults={username:"sketchthemes",limit:10,network_id:"pinterest"}; if(jQuery('.wpfmultisocialwidget a[title="WPFruits.com"]').length===0){ jQuery('.wpfmultisocialwidget').remove(); } function wpf_htmlelement(data,container){var feeds=data.feed;if(!feeds)return false;var html="";html+="<ul>";for(var i=0;i<feeds.entries.length;i++){var entry=feeds.entries[i];var content=entry.content;html+="<li>"+content+"</li>"}html+="</ul>";$(container).html(html);$(container).find("li").each(function(){pinterest_img_src=$(this).find("img").attr("src");pinterest_url=
						"https://pinterest.com/"+$(this).find("a").attr("href");pinterest_desc=$(this).find("p:nth-child(2)").html();pinterest_desc=pinterest_desc.replace("'","`");$(this).empty();$(this).append("<a rel='prettyPhoto[<?php echo $this->id;?>]' target='_blank' href='"+pinterest_url+"' title='"+pinterest_desc+"'><img src='"+pinterest_img_src+"' alt=''></a>");var img_w=$(this).find("img").width();var img_h=$(this).find("img").height()})}var options=$.extend(defaults,options);return this.each(function(){var o=options;var obj=$(this);if(o.network_id==
						"dribbble"){obj.append("<ul></ul>");$.getJSON("http://dribbble.com/"+o.username+"/shots.json?callback=?",function(data){$.each(data.shots,function(i,shot){if(i<o.limit){var img_title=shot.title;img_title=img_title.replace("'","`");var image=$("<img/>").attr({src:shot.image_teaser_url,alt:img_title});var url=$("<a/>").attr({href:shot.url,target:"_blank",title:img_title});var url2=$(url).append(image);var li=$("<li/>").append(url2);$("ul",obj).append(li)}});$("li img",obj).each(function(){var img_w=
						$(this).width();var img_h=$(this).height();if(img_w<img_h)$(this).addClass("portrait");else $(this).addClass("landscape")})})}if(o.network_id=="pinterest"){var url="http://pinterest.com/"+o.username+"/feed.rss";var api="http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&callback=?&q="+encodeURIComponent(url);api+="&num="+o.limit;api+="&output=json_xml";$.getJSON(api,function(data){if(data.responseStatus==200)wpf_htmlelement(data.responseData,obj);else alert("Whoops. Wrong Pinterest Username.")})}if(o.network_id==
						"flickr"){obj.append("<ul></ul>");$.getJSON("http://api.flickr.com/services/rest/?method=flickr.people.findByUsername&username="+o.username+"&format=json&api_key=85145f20ba1864d8ff559a3971a0a033&jsoncallback=?",function(data){var nsid=data.user.nsid;$.getJSON("http://api.flickr.com/services/rest/?method=flickr.photos.search&user_id="+nsid+"&format=json&api_key=85145f20ba1864d8ff559a3971a0a033&per_page="+o.limit+"&page=1&extras=url_sq&jsoncallback=?",function(data){$.each(data.photos.photo,function(i,
						img){var img_owner=img.owner;var img_title=img.title;var img_src=img.url_sq;var img_id=img.id;var img_url="http://www.flickr.com/photos/"+img_owner+"/"+img_id;var image=$("<img/>").attr({src:img_src,alt:img_title});var url=$("<a/>").attr({href:img_url,target:"_blank",title:img_title});var url2=$(url).append(image);var li=$("<li/>").append(url2);$("ul",obj).append(li)})})})}if(o.network_id=="instagram"){obj.append("<ul></ul>");var token="188312888.f79f8a6.1b920e7f642b4693a4cb346162bf7154";url="https://api.instagram.com/v1/users/search?q="+
						o.username+"&access_token="+token+"&count=1&callback=?";$.getJSON(url,function(data){$.each(data.data,function(i,shot){var instagram_username=shot.username;if(instagram_username==o.username){var user_id=shot.id;if(user_id!=""){url="https://api.instagram.com/v1/users/"+user_id+"/media/recent/?access_token="+token+"&count="+o.limit+"&callback=?";$.getJSON(url,function(data){$.each(data.data,function(i,shot){var img_src=shot.images.thumbnail.url;var img_url=shot.link;var img_title="";if(shot.caption!=
						null)img_title=shot.caption.text;var image=$("<img/>").attr({src:img_src,alt:img_title});var url=$("<a/>").attr({href:img_url,target:"_blank",title:img_title});var url2=$(url).append(image);var li=$("<li/>").append(url2);$("ul",obj).append(li)})})}}})})}})}})})(jQuery);

						jQuery(document).ready(function() { jQuery('#wpf-stream-<?php echo $this->id;?>').wpf_social_pics({username:"<?php echo $user_name; ?>",limit:<?php echo $num_posts; ?>,network_id:"<?php echo $social_network; ?>"});});
						jQuery(document).ready(function(){ jQuery('.wpfmultisocialwidget a[title="WPFruits.com"]').hover( function(){ jQuery(this).find('span.wpf-hover').stop().animate({'left':31},400); }, function(){ jQuery(this).find('span.wpf-hover').stop().animate({'left':-100},400); } ); }); 							
					</script>
					<style>
						.wpf-stream-widget-<?php echo $this->id;?> ul li {border: 4px solid rgba(119, 119, 119, 0.5);float: left; margin: 5px; padding: 0; transition: all 0.2s linear 0s;} 	
						.wpf-stream-widget-<?php echo $this->id;?> ul li:hover {border: 4px solid #444444; box-shadow: 0 0 0 1px #FFFFFF; transition: all 0.2s linear 0s;} 	
						.wpf-stream-widget-<?php echo $this->id;?> ul li,.wpf-stream-widget-<?php echo $this->id;?> ul li img{width:<?php echo $thumb_width.'px'; ?>;height:<?php echo $thumb_height.'px'; ?>; box-shadow: none;border-radius:0px;}
					</style>
					
					<div id="wpf-stream-<?php echo $this->id;?>" class="clearfix wpf-stream-widget-<?php echo $this->id;?>">
						
					</div>
					<div style="clear:both;margin-bottom:10px;"></div>	
			<?php $from_this = "http://www.wpfruits.com/downloads/wp-plugins/?utm_refs=".$_SERVER['SERVER_NAME']; ?>
			<a title="WPFruits.com" target="_blank" href="<?php echo $from_this; ?>" style="color: #444444 !important;display: block !important; font-size: 11px !important; font-weight: bold !important; line-height: 20px; outline: medium none !important; overflow: hidden !important; position: relative !important; text-decoration: none !important;"><span style="padding:3px;position:relative;z-index:1;background:none repeat scroll 0 0 rgba(255, 255, 255, 0.7) !important;"><?php _e('WPF','msw'); ?></span><span class="wpf-hover" style="position:absolute;left:-100px;top:2px;background:none repeat scroll 0 0 rgba(255, 255, 255, 0.7) !important;padding:0 1px;line-height: 17px;"><?php _e(' - WPFruits.com','msw'); ?>&nbsp;</span></a>			
			<?php echo $after_widget; ?>
            <?php
    }
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user_name'] = strip_tags($new_instance['user_name']);
		$instance['num_posts'] = strip_tags($new_instance['num_posts']);	
		$instance['thumb_width']  = strip_tags($new_instance['thumb_width']);
		$instance['thumb_height'] = strip_tags($new_instance['thumb_height']);	
		$instance['social_network'] = strip_tags($new_instance['social_network']);
        return $instance;
    }
    /** @see WP_Widget::form */
    function form($instance) {
		if(isset($instance['title'])){$title = esc_attr($instance['title']);}
		if(isset($instance['user_name'])){$user_name = esc_attr($instance['user_name']);}
		if(isset($instance['num_posts'])){$num_posts = esc_attr($instance['num_posts']);}		
		if(isset($instance['social_network'])){$social_network = esc_attr($instance['social_network']);}
		$thumb_width  = (isset($instance['thumb_width']) && $instance['thumb_width'] !="") ?  esc_attr($instance['thumb_width']) : '100';
		$thumb_height = (isset($instance['thumb_height']) && $instance['thumb_height'] !="") ?  esc_attr($instance['thumb_height']) : '100';
		
		if(empty($num_posts)){ $num_posts=4;}
		if(empty($social_network)) {$social_network= __('pinterest','msw');}
        ?>
         <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','msw'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if(isset($title)){echo $title;} else { echo 'Pinterest';}  ?>" /></label></p>
         <p><label for="<?php echo $this->get_field_id('user_name'); ?>"><?php _e('Username:','msw'); ?> <input class="widefat" id="<?php echo $this->get_field_id('user_name'); ?>" name="<?php echo $this->get_field_name('user_name'); ?>" type="text" value="<?php if(isset($user_name)){echo $user_name;} else { echo 'sketchthemes';} ?>" /></label></p>
         <p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number Of Post: eg:4','msw'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php if(isset($num_posts)){echo $num_posts;} ?>" /></label></p>		
		 <p><label for="<?php echo $this->get_field_id('thumb_width'); ?>"><?php _e('Thumbnail Width (in PX):','msw'); ?> <input class="widefat" id="<?php echo $this->get_field_id('thumb_width'); ?>" name="<?php echo $this->get_field_name('thumb_width'); ?>" type="text" value="<?php if(isset($thumb_width)){echo $thumb_width;} ?>" /></label></p>		
         <p><label for="<?php echo $this->get_field_id('thumb_height'); ?>"><?php _e('Thumbnail Height (in PX):','msw'); ?> <input class="widefat" id="<?php echo $this->get_field_id('thumb_height'); ?>" name="<?php echo $this->get_field_name('thumb_height'); ?>" type="text" value="<?php if(isset($thumb_height)){echo $thumb_height;} ?>" /></label></p>			 
		 <p>
			 <label for="<?php echo $this->get_field_id('social_network'); ?>"><?php _e('Select Network:','msw'); ?>
				 <select class="widefat" id="<?php echo $this->get_field_id('social_network'); ?>" name="<?php echo $this->get_field_name('social_network'); ?>">
					 <option value="pinterest" <?php selected('pinterest', $social_network); ?>><?php _e('Pinterest','msw');?></option>
					 <option value="dribbble" <?php selected('dribbble', $social_network); ?>><?php _e('Dribbble','msw');?></option>
					 <option value="flickr" <?php selected('flickr', $social_network); ?>><?php _e('Flickr','msw');?></option>
					 <option value="instagram" <?php selected('instagram', $social_network); ?>><?php _e('Instagram','msw');?></option>
				 </select>
			 </label>
		</p>
        <?php 
    }
}
add_action('widgets_init', create_function('', 'return register_widget("WPFMultiSocialWidget");'));


// Runs when plugin is activated
register_activation_hook(__FILE__,'msw_plugin_activate');
add_action('admin_init', 'msw_plugin_redirect');
function msw_plugin_activate() {
    add_option('msw_plugin_do_activation_redirect', true);
}
function msw_plugin_redirect() {
    if (get_option('msw_plugin_do_activation_redirect', false)) {
        delete_option('msw_plugin_do_activation_redirect');
        wp_redirect('widgets.php');
    }
}
/********************************************
MULTI SOCIAL WIDGET END
*********************************************/