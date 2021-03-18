<?php
/**
 * The file that defines the posts
 *
 * @link       www.faboba.com
 * @since      1.0
 *
 * @package    Falang
 */

namespace Falang\Table;

use Falang\Core\Falang_Core;
use Falang\Model\Falang_Model;

class Post extends \WP_List_Table {

	private $language_list;
	private $model;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct(Falang_Model $model) {
		parent::__construct(
			array(
				'plural' => 'Posts', // Do not translate ( used for css class )
				'ajax'   => false,
			)
		);
		$this->model = $model;
		$this->language_list = $this->model->get_languages_list(array( 'hide_default' => true));

	}

	/**
	 * Displays the item information in a column ( default case )
	 *
	 * @since 1.0
	 *
	 * @param object $item
	 * @param string $column_name
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		$language_list_locale = $this->model->get_available_locales();
		switch ( $column_name ) {
			case 'post_type':
				return esc_html( $item->$column_name );
			default:
				//language column fr_FR, en_US, ...
				if (in_array($column_name,$language_list_locale)){
					return $this->display_translation_post_action($item,$column_name);
				} else {
					return print_r( $item, true ); //Show the whole array for troubleshooting purposes
				}
		}
	}

	/**
	 * Displays the item information in the column 'name'
	 *
	 * @since 1.0
	 *
	 * @param object $item
	 * @return string
	 */
	public function column_name( $item ) {
		return esc_html( $item->post_title );
	}

	/**
	 * Displays the item information in the column 'name'
	 *
	 * @since 1.0
	 *
	 * @param object $item
	 * @return string
	 */
	public function column_locale( $item ) {
		$post_locale = get_post_meta($item->ID,'_locale' , true);
		if (empty($post_locale) || 'all' == $post_locale) {
			$post_locale = esc_html__( 'All languages', 'falang' );
		}
		return esc_html($post_locale);
	}

	/**
	 * Displays the id information in the column 'id'
	 *
	 * @since 1.0.6
	 *
	 * @param object $item
	 * @return string
	 */
	public function column_id( $item ) {
		return esc_html($item->ID);
	}
	/**
	 * Gets the list of columns
	 *
	 * @since 0.1
	 *
	 * @return array the list of column titles
	 */
	public function get_columns() {

		$columns = array(
			'name'         => __( 'Name', 'falang' ),
			'post_type'  => _x( 'Type', 'falang' ),
			'locale'       => _x( 'Locale', 'falang' )
		);

		//add language column
		//language list is only non default language
		foreach ($this->language_list as $language){
				$columns[$language->locale] = $language->get_flag();

		}

		//add ID column to the end
        $columns['id'] =  _x( 'ID', 'falang' );

		return $columns;
	}


	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 2.1
	 *
	 * @return string Name of the default primary column, in this case, 'name'.
	 */
	protected function get_default_primary_column_name() {
		return 'name';
	}

	/** ************************************************************************
	 * REQUIRED! This is where you prepare your data for display. This method will
	 * usually be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args(), although the following properties and methods
	 * are frequently interacted with here...
	 *
	 * @since 1.0
	 *
	 * @param array $data
	 *
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 **************************************************************************/
	public function prepare_items( $data = array()) {
		// Filter for search post
		$s = empty( $_REQUEST['s'] ) ? '' : wp_unslash( $_REQUEST['s'] );

		foreach ( $data as $key => $row ) {
//			if ( ( -1 !== $this->selected_group && $row['context'] !== $this->selected_group ) || ( ! empty( $s ) && stripos( $row['name'], $s ) === false && stripos( $row['string'], $s ) === false ) ) {
			if ( (!empty( $s ) && $row->ID != (int)$s)  && ( ! empty( $s ) && stripos( $row->post_title, $s ) === false) ) {
				unset( $data[ $key ] );
			}
		}


		$per_page = $this->get_items_per_page('falang_post_per_page',20);
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$total_items = count( $data );
		$this->items = array_slice( $data, ( $this->get_pagenum() - 1 ) * $per_page, $per_page );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}

	// status filter links
	// http://wordpress.stackexchange.com/questions/56883/how-do-i-create-links-at-the-top-of-wp-list-table
	function get_views() {
		$views      = array();
		//TODO check remove query args
		$base_url   = esc_url_raw( remove_query_arg( array( 'post_type' ) ) );

		// handle search query
		if ( isset($_REQUEST['s']) && $_REQUEST['s'] ) {
			$base_url = add_query_arg( 's', $_REQUEST['s'], $base_url );
		}

		// handle post_type filter
		if ( isset($_REQUEST['pt-filter']) && $_REQUEST['pt-filter'] ) {
			$base_url = add_query_arg( 'post_type', $_REQUEST['pt-filter'], $base_url );
		}

		return $views;

	}


	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( 'top' != $which ) return;
		$post_type = $this->model->get_transtable_post_types(true);
		?>
			<div class="alignleft actions bulkactions">
				<select name="pt-filter" class="post-type-filter">
					<option value=""><?php _e('Filter by Post Type','falang'); ?></option>
					<?php  foreach ($post_type as $pt) {
					    $selected = '';
					    if( isset($_REQUEST['pt-filter']) && ($_REQUEST['pt-filter'] == $pt) ){$selected = ' selected = "selected"';}
					    ?>
					    <option value="<?php echo $pt; ?>" <?php echo $selected; ?>><?php echo $pt; ?></option>
					    <?php
				    }
					?>
				</select>
				<input type="submit" name="" id="pt_filter_btn" class="button" value="<?php _e('Filter', 'falang') ?>">
			</div>
		<?php
	}


	private function display_translation_post_action($item,$locale){
		//only display action if post locale is set to all (or missing)
		//TODO make a method on Translation Post
		$post_locale = get_post_meta($item->ID,'_locale' , true);

		$page = falang_clean($_REQUEST['page']);
		if ( isset( $_REQUEST['paged'] ))           $page .= '&paged='.falang_clean($_REQUEST['paged']);
		if ( isset( $_REQUEST['s'] ))               $page .= '&s=' . urlencode( falang_clean($_REQUEST['s']) );
		if ( isset( $_REQUEST['pt-filter'] ))           $page .= '&pt-filter='.falang_clean($_REQUEST['pt-filter']);

		if (!empty($post_locale)){return '&nbsp;<div class="row-actions"><span class="edit">&nbsp;</span> </div>';}

		$language_list_locale = $this->model->get_available_locales(array('exclude' => 'all'));

		if (in_array($post_locale ,$language_list_locale)){
			return false;
		}

		//get status
		$post_status = get_post_meta($item->ID,Falang_Core::get_prefix($locale).'published' , true);
		$status = '<span class="dashicons dashicons-marker" style="font-size: 13px;line-height: 1.5em;color:grey"></span>';
		if (!empty($post_status)){
			if ($post_status){
				$status = '<span class="dashicons dashicons-yes-alt" style="font-size: 13px;line-height: 1.5em;color:green"></span>';
			} else {
				$status = '<span class="dashicons dashicons-dismiss" style="font-size: 13px;line-height: 1.5em;color:red"></span>';
			}
		}
		//get translation title
		$header_title = '<i style="color: grey">'.$item->post_title.'</i>';
		$title_locale = get_post_meta($item->ID,Falang_Core::get_prefix($locale).'post_title' , true);
		if (!empty($title_locale)){
			$header_title = $title_locale;
		}

		$actions = array(
			'edit'   => sprintf(
				'<a title="%s" href="%s">%s</a>',
				esc_attr__( 'Edit', 'falang' ),
				esc_url( admin_url( 'admin.php?page='.$page.'&amp;action=edit&amp;post_id=' . $item->ID . '&amp;language=' . $locale ) ),
				esc_html__( 'Edit', 'falang' )
			),
			'delete' => sprintf(
				'<a class="ajax-delete-action"  title="%s" href="%s">%s</a>',
				esc_attr__( 'Delete Post data for this translation', 'falang' ),
				wp_nonce_url( 'admin-ajax.php?action=falang_post_delete_translation&amp;post_id=' . $item->ID . '&amp;language=' . $locale, 'delete-post-translation' ),
				//esc_js( __( 'You are about to permanently delete this translation. Are you sure?', 'falang' ) ),
				esc_html__( 'Delete', 'falang' )
			),
		);
		/**
		 * Filter the list of row actions in the languages list table
		 *
		 * @since 1.0
		 *
		 * @param array  $actions list of html markup actions
		 * @param object $item
		 */
		$actions = apply_filters( 'falang_translate_post_actions', $actions, $item );

		$row_header = $status.' '.$header_title;

		return sprintf("%s %s",$row_header,$this->row_actions( $actions,false ));

		//return $this->row_actions( $actions,true );
	}

}