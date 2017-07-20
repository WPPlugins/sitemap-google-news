<?php 
/**
* Google News Sitemap Generate XML Postes
*/
class GNS_Posts
{
	public function gns_metabox()
	{
		$posts = get_option( 'gns_posts' );
		for ( $i=0; $i < count( $posts ); $i++ ) { 
			add_meta_box( 'gns_the_metabox', 'Google News Sitemap', array( 'GNS_Posts', 'gns_metabox_edit' ), $posts[ $i ], 'normal', 'high', array( 'id' => 'gns_the_metabox' ) );
		}
	}

	public function gns_metabox_edit( $post )
	{
		wp_nonce_field( 'gns_meta_box', 'gns_meta_box_nonce' );
		$html .= '
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"></th>
						<td>
		';
		$values = get_post_meta( $post->ID, 'gns_exclude', true );
		if ( $values ) {
			$checked = ( in_array( 'yes', $values ) ) ? 'checked="checked"' : '';
		}
		$html .= '
							<p>
								<label>
									<input name="gns_exclude[]" value="yes" ' . $checked . ' class="tog" type="checkbox">
									Exclude News Sitemap
								</label>
							</p>
							<p class="description"></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="gns_keyword">Keywords</label></th>
						<td>
							<input name="gns_keyword" id="gns_keyword" value="' . esc_attr( get_post_meta( $post->ID, 'gns_keyword', true ) ) . '" class="regular-text" type="text">
							<p class="description"></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="gns_genre">Genre</label></th>
						<td>
		';
		$genre_value = get_post_meta( $post->ID, 'gns_genre', true );
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
						<th scope="row"><label for="gns_stock_tickers">Stock Tickers</label></th>
						<td>
							<input name="gns_stock_tickers" id="gns_stock_tickers" value="' . esc_attr( get_post_meta( $post->ID, 'gns_stock_tickers', true ) ) . '" class="regular-text" type="text">
							<p class="description">"NASDAQ:AMAT" (but not "NASD:AMAT"), or "BOM:500325" (but not "BOM:RIL")</p>
						</td>
					</tr>
				</tbody>
			</table>
		';
		echo $html;	
	}

	public function gns_metabox_save( $post_id )
	{
		$fields = array(
			'gns_exclude',
			'gns_keyword',
			'gns_genre',
			'gns_stock_tickers'
		);

		for ( $i=0; $i < count( $fields ); $i++ ) { 
			add_post_meta( $post_id, $fields[ $i ], $_POST[ $fields[ $i ] ], true ) or update_post_meta( $post_id, $fields[ $i ], $_POST[ $fields[ $i ] ] );
		}

		// $generate = new GNS_Generate( 'create' );
	}
}
?>