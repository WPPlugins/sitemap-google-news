<?php 
/**
* Google News Sitemap Admin
*/
class GNS_Admin
{
	
	function __construct( $method )
	{
		switch ( $method ) {
			case 'create':
				$this->gns_create();
				break;
		}
	}

	private function gns_create()
	{
		add_action( 'admin_menu',	array( $this, 'gns_add_page' ) );
	}

	public function gns_add_page() {
		add_options_page( 'Google News Sitemap', 'Google News Sitemap', 'manage_options', basename(__FILE__), array( $this, 'gns_admin_page' ) );
	}

	public function gns_admin_page()
	{
		$this->gns_save();
		
		$html .= '
			<div class="wrap">
				<h2>Google News Sitemap Settings</h2>
		';
		$html .= '<p><a href="' . get_home_url() . '/news-sitemap.xml" target="_blank">View News Sitemap</a></p>';
		$html .= '
				<form method="post">
					' . wp_nonce_field( 'gns_options', 'gns_options_nonce' ) . '
					<table class="form-table">
						<tbody>
						<tr>
							<th scope="row"><label for="gns_name">Google News Publication Name</label></th>
							<td>
								<input name="gns_name" id="gns_name" value="' . esc_attr( get_option( 'gns_name' ) ) . '" class="regular-text" type="text">
								<p class="description"></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gns_genre">Default Genre</label></th>
							<td>
		';
		$genre_value = get_option( 'gns_genre' );
		$values = ( is_array( $genre_value ) ) ? $genre_value : array( $genre_value );

		$genre = new GNS_Utils( 'genre' );
		foreach ( $genre->genre as $value => $label ) {
			if ( $values ) {
				$checked = ( in_array( $value, $values ) ) ? 'checked="checked"' : '';
			}
			$html .= '
								<p>
									<label>
										<input name="gns_genre[]" value="' . $value . '" ' . $checked . ' class="tog" type="checkbox">
										' . $label . '
									</label>
								</p>
			';
		}
		$html .= '
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gns_keyword">Defaut Keywords</label></th>
							<td>
								<input name="gns_keyword" id="gns_keyword" value="' . esc_attr( get_option( 'gns_keyword' ) ) . '" class="regular-text" type="text">
								<p class="description"></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gns_posts">Post Type Includes Sitemap</label></th>
							<td>
		';
		$posts_value = get_option( 'gns_posts' );
		$values = ( is_array( $posts_value ) ) ? $posts_value : array( $posts_value );

		$posts = new GNS_Utils( 'posts' );
		foreach ( $posts->post as $value => $label ) {
			if ( $values ) {
				$checked = ( in_array( $value, $values ) ) ? 'checked="checked"' : '';
			}
			$html .= '
								<p>
									<label>
										<input name="gns_posts[]" value="' . $value . '" ' . $checked . ' class="tog" type="checkbox">
										' . $label . '
									</label>
								</p>
			';
		}

		$html .= '
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gns_lang">Language</label></th>
							<td>
		';
		$language = new GNS_Utils( 'language' );
		foreach ( $language->lang as $value => $label ) {
			$checked = ( get_option( 'gns_lang' ) == $value ) ? 'checked="checked"' : '';
			$html .= '
								<p>
									<label>
										<input name="gns_lang" value="' . $value . '" ' . $checked . ' class="tog" type="radio">
										' . $label . '
									</label>
								</p>
			';
		}
		$html .= '
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gns_access">Article Access</label></th>
							<td>
		';
		$access = new GNS_Utils( 'access' );
		foreach ( $access->access as $value => $label ) {
			$checked = ( get_option( 'gns_access' ) == $value ) ? 'checked="checked"' : '';
			$html .= '
								<p>
									<label>
										<input name="gns_access" value="' . $value . '" ' . $checked . ' class="tog" type="radio">
										' . $label . '
									</label>
								</p>
			';
		}
		$html .= '
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="gns_stock_tickers">Stock Tickers</label></th>
							<td>
								<input name="gns_stock_tickers" id="gns_stock_tickers" value="' . esc_attr( get_option( 'gns_stock_tickers' ) ) . '" class="regular-text" type="text">
								<p class="description">"NASDAQ:AMAT" (but not "NASD:AMAT"), or "BOM:500325" (but not "BOM:RIL")</p>
							</td>
						</tr>
						</tbody>
					</table>
					<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Salvar alterações" type="submit"></p>
				</form>
			</div>
		';

		echo $html;
	}

	public function gns_save()
	{
		if ( $_POST ) {
			if ( !isset( $_POST[ 'gns_options_nonce' ] ) ) {
				wp_die( 'Você não possui permissões suficientes para editar essa página!' );
			}
			if ( !wp_verify_nonce( $_POST[ 'gns_options_nonce' ], 'gns_options' ) ) {
				wp_die( 'Você não possui permissões suficientes para editar essa página!' );
			}

			$fields = array(
				'gns_name',
				'gns_genre',
				'gns_keyword',
				'gns_posts',
				'gns_lang',
				'gns_access',
				'gns_stock_tickers'
			);

			for ( $i=0; $i < count( $fields ); $i++ ) { 
				if ( get_option( $fields[ $i ] ) ) {
					update_option( $fields[ $i ], $_POST[ $fields[ $i ] ] );
				} else {
					add_option( $fields[ $i ], $_POST[ $fields[ $i ] ] );
				}
			}

			echo '<div class="updated"><p>Opções atualizadas com sucesso!</p></div>';
		}
	}
}
?>