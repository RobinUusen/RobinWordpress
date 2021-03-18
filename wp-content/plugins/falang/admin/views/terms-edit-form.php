<?php
/**
 *
 * Displays Term translation in Categories/Tags
 *
 * @link       www.faboba.com
 * @since      1.2.4
 *
 * @package    Falang
 * @subpackage Falang/admin/views
 */

$languages = $this->model->get_languages_list();
$falang_taxonomy = new \Falang\Core\Taxonomy( null, $this->model );
?>
<tr>
	<th><h2><?php echo __('Translations', 'falang'); ?></h2></th>
<td><?php wp_nonce_field('falang', 'falang_term_nonce', false, true); ?></td>
</tr>

<?php foreach ($languages as $language) { ?>
	<?php
	if ($this->is_default($language)) continue;
	$slug = $falang_taxonomy->translate_term_field($tag, $taxonomy, 'slug', $language, '');
	$name = $falang_taxonomy->translate_term_field($tag, $taxonomy, 'name', $language, '');
	$desc = $falang_taxonomy->translate_term_field($tag, $taxonomy, 'description', $language, '');
	$published = $falang_taxonomy->translate_term_field($tag, $taxonomy, 'published', $language, '');

	?>
	<tr>
		<th>
			<label><?php echo $language->name.' '.$language->get_flag(); ?></label>
            <label class="falang-switch">
                <input type="checkbox" name="falang_term[<?php echo $taxonomy; ?>][<?php echo $language->locale; ?>][published]"  value="1" <?php echo $published ? ' checked' : ''; ?>/>
                <span class="slider"></span>
            </label>
        </th>
		<td>
			<div style="display:flex;display: -webkit-flex;flex-wrap:wrap;-webkit-flex-wrap:wrap">
				<div style="margin-bottom:1em">
					<input name="falang_term[<?php echo $taxonomy; ?>][<?php echo $language->locale; ?>][name]" type="text" value="<?php echo $name; ?>" placeholder="<?php echo $tag->name; ?>" size="40" style="box-sizing:border-box">
					<p class="description"><?php echo __('Term Name', 'falang'); ?></p>
				</div>
				<div style="margin-bottom:1em">
					<input name="falang_term[<?php echo $taxonomy; ?>][<?php echo $language->locale; ?>][slug]" type="text" value="<?php echo $slug; ?>" placeholder="<?php echo $tag->slug; ?>" size="40" style="box-sizing:border-box">
					<p class="description"><?php echo __('Term slug', 'falang'); ?></p>
				</div>
				<div style="margin-bottom:1em; width:100%">
					<textarea name="falang_term[<?php echo $taxonomy; ?>][<?php echo $language->locale; ?>][description]" style="box-sizing:border-box;width:95%;"><?php echo $desc; ?></textarea>
					<p class="description"><?php echo __('Term description.', 'falang'); ?></p>
				</div>
			</div>
		</td>
	</tr>
<?php }