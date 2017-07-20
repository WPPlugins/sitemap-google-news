<?php 
/**
* Google News Sitemap Utils
*/
class GNS_Utils
{
	public $genre;
	public $post;
	public $lang;
	public $access;

	function __construct( $method )
	{
		switch ( $method ) {
			case 'genre':
				$this->gns_set_genre();
				break;
			case 'posts':
				$this->gns_set_posts_type();
				break;
			case 'language':
				$this->gns_set_languages();
				break;
			case 'access':
				$this->gns_set_access();
				break;
		}
	}

	protected function gns_set_genre()
	{
		$genres = array(
			''				=> 'Not Applicable',
			'Blog'			=> 'Blog',
			'PressRelease'	=> 'Press Release',
			'UserGenerated'	=> 'UserGenerated',
			'Satire'		=> 'Satire',
			'OpEd'			=> 'OpEd',
			'Opinion'		=> 'Opinion'
		);

		$this->genre = $genres;
	}

	protected function gns_set_posts_type()
	{
		$post_types = get_post_types( '', 'objects' ); 
		foreach ( $post_types as $post_type ) {
			if ( $post_type->name != 'attachment' && $post_type->name != 'revision' && $post_type->name != 'nav_menu_item' ) {
				$obj = get_post_type_object( $post_type->name );
				$posts[ $post_type->name ] =  $obj->labels->name;
			}
		}

		$this->post = $posts;
	}

	protected function gns_set_languages()
	{
		$langs = array(
			'pt' 	=> 'Portuguese',
			'en'	=> 'English',
			'es'	=> 'Spanish'
		);

		$this->lang = $langs;
	}

	protected function gns_set_access()
	{
		$access_ = array(
			''				=> 'Not Applicable',
			'Subscription'	=> 'Subscription',
			'Registration'	=> 'Registration'
		);

		$this->access = $access_;
	}
}
?>