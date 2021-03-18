<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.faboba.com
 * @since      1.0
 *
 * @package    Falang
 * @subpackage Falang/admin/partials
 */
?>
<script type="text/javascript">
    jQuery(document).ready(function(){


        jQuery( ".button-link-delete" ).on( "click", function(event) {
            if( ! confirm( 'You are about to permanently delete this language. Are you sure ?' ) ) {
                event.preventDefault();
            }
        });

        function formatSelectFlag (Language) {
            if (!Language.id) {
                return Language.text;
            }
            var baseUrl = "<?php echo plugins_url( "/flags", FALANG_FILE ) ?>";
            var $language = jQuery(
                '<span><img src="' + baseUrl + '/' + Language.element.value.toLowerCase() + '.png" class="img-flag" />&nbsp;' + Language.text + '</span>'
            );
            return $language;
        }

        function formatDisplayFlag (Language) {
            if (!Language.id) {
                return Language.text;
            }

            var baseUrl = "<?php echo plugins_url( "/flags", FALANG_FILE ) ?>";
            var $language = jQuery(
                '<span><img class="img-flag" /> <span></span></span>'
            );

            // Use .text() instead of HTML string concatenation to avoid script injection issues
            $language.find("span").text(Language.text);
            $language.find("img").attr("src", baseUrl + "/" + Language.element.value.toLowerCase() + ".png");

            return $language;
        };


        jQuery('#flag').select2({
            templateResult: formatSelectFlag,
            templateSelection: formatDisplayFlag
        });

    });

</script>

<?php if ( !empty($falang_language)): ?>
    <h1><?php echo __('Edit this language','falang') ?></h1>
<?php else: ?>
    <h1><?php echo __('Add New Language','falang') ?></h1>
<?php endif; ?>

<!-- Display info message on edition -->
<?php if (!empty($falang_language)){?>
    <div class="update-nag notice">
        <p><?php _e( 'Don\'t change language Locale, it\'s not yet supported!', 'falang' );?></p>
    </div>
<?php } ?>


<form id="add-language" method="post" action="<?php echo $falang_form_action; ?>" class="validate">
    <table class="form-table">
        <tbody>
        <tr>
            <th><?php esc_html_e( 'Choose a language', 'falang' ); ?></th>
            <td>
                <select name="language_list" id="language_list">
                    <option value=""></option>
		            <?php
		            foreach ( $falang_predefined_language_list as $lg ) {
			            printf(
				            '<option value="%1$s:%2$s:%3$s:%4$s" %6$s>%5$s - %2$s</option>' . "\n",
				            esc_attr( $lg['code'] ),
				            esc_attr( $lg['locale'] ),
				            'rtl' == $lg['dir'] ? '1' : '0',
				            esc_attr( $lg['flag'] ),
				            esc_html( $lg['name'] ),
				            isset( $falang_language->locale ) && $falang_language->locale == $lg['locale'] ? ' selected="selected"' : ''
			            );
		            }
		            ?>
                </select>
                <p class="description">
	                <?php esc_html_e( 'You can choose a language in the list or directly edit it below.', 'falang' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Full name', 'falang' ); ?></th>
            <td>
	            <?php
	            printf(
		            '<input name="name" id="language_name" type="text" value="%s" size="40" aria-required="true" />',
		            ! empty( $falang_language ) ? esc_attr( $falang_language->name ) : ''
	            );
	            ?>
                <p class="description">
	                <?php esc_html_e( 'The name decides how it is displayed on your site (for example: English)', 'falang' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Locale', 'falang' ); ?></th>
            <td>
	            <?php
	            printf(
		            '<input name="locale" id="language_locale" readonly type="text" value="%s" size="40" aria-required="true" />',
		            ! empty( $falang_language ) ? esc_attr( $falang_language->locale ) : ''
	            );
	            ?>

                <p class="description">
	                <?php esc_html_e( 'WordPress Locale for the language (for example: en_US). The .mo file will be installed automatically for this language.', 'falang' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'SEF language code', 'falang' ); ?></th>
            <td>
	            <?php
	            printf(
		            '<input name="slug" id="language_slug" type="text" value="%s" size="40"/>',
		            ! empty( $falang_language ) ? esc_attr( $falang_language->slug ) : ''
	            );
	            ?>
                <p class="description">
	                <?php esc_html_e( 'Language code - preferably 2-letters ISO 639-1  (for example: en)', 'falang' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Text direction', 'falang' ); ?></th>
            <td>
	            <?php
	            printf(
		            '<label><input name="rtl" type="radio" value="0" %s /> %s</label>',
		            ! empty( $edit_lang ) && $edit_lang->is_rtl ? '' : 'checked="checked"',
		            esc_html__( 'Left to Right', 'falang' )
	            );
	            printf(
		            '<label><input name="rtl" type="radio" value="1" %s /> %s</label>',
		            ! empty( $edit_lang ) && $edit_lang->is_rtl ? 'checked="checked"' : '',
		            esc_html__( 'Right to Left', 'falang' )
	            );
	            ?>

                <p class="description">
	                <?php esc_html_e( 'Choose the text direction for the language', 'falang' ); ?>
                </p>
            </td>
        </tr>
        <!-- FLAG -container -->
        <tr>
            <th><?php esc_html_e( 'Flag', 'falang' ); ?></th>
            <td>
                <select name="flag" id="flag" >
                    <option value=""></option>
		            <?php
		            foreach ( $falang_flag_list as $code => $label ) {
			            /** This filter is documented in Falang/Core/language.php */
			            $flag = apply_filters( 'falang_flag', array( 'url' => plugins_url( "/flags/{$code}.png", FALANG_FILE ) ), $code );
			            ?>
			            <option <?php isset($falang_language)?selected( $falang_language->flag_code === $code ):''; ?>
                            value="<?php echo esc_attr( $code )?>"><?php echo esc_html($label)?></option>
                        <?php
		            }
		            ?>
                </select>
                <p class="description">
	                <?php esc_html_e( 'Choose a flag for the language', 'falang' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th></th>
            <td>
                <p class="description">
                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- position set to 0 -->
    <input type="hidden" name="position" value="0">
    <?php
    if ( ! empty( $falang_language ) ) {
            ?>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="language_id" value="<?php echo esc_attr( $falang_language->term_id ); ?>" />
            <?php
        } else {
            ?>
            <input type="hidden" name="action" value="add" />
            <?php
        }
    ?>
    <div id="col-container">
        <div class="col-wrap">
            <div class="row">
                <?php submit_button( ! empty( $falang_language ) ? __( 'Update' ) : __( 'Add New Language', 'falang' ),'primary','submit', false ); ?>
                    <a class="button button-primary cancel-edit" href="<?php echo $falang_cancel_action; ?>"><?php echo __( 'Cancel', 'falang' );?> </a>
	            <?php if ( !empty($falang_language)): ?>
                    <a class="button button-link-delete" href="<?php echo $falang_delete_action; ?>"><i class="dashicons dashicons-trash"></i> <?php echo __( 'Delete Language', 'falang' );?> </a>
	            <?php endif ?>
            </div>
        </div><!-- col-container -->
    </div><!-- col-container -->
</form>
