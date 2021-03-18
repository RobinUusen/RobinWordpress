<table class="form-table">
	<tbody>
    <tr>
        <th><?php _e('Version', 'falang'); ?></th>
        <td>
            <label>
                <p><?php echo $falang_version;?></p>
            </label>
        </td>
    </tr>
	<tr>
		<th><?php _e('Show slug for main language', 'falang'); ?></th>
		<td>
			<label>
				<input type="checkbox" name="show_slug" value="1"<?php echo $falang_model->get_option('show_slug') ? ' checked' : '' ?>/>
				<?php _e('Show slug for main language', 'falang') ?>
			</label>
		</td>
	</tr>
	<tr>
		<th><?php _e('Auto-detect language', 'falang'); ?></th>
		<td>
			<label>
				<input type="checkbox" name="autodetect" value="1"<?php echo $falang_model->get_option('autodetect') ? ' checked' : ''; ?>/>
				<?php _e('Auto-detect language when language is not specified in url', 'falang'); ?>
			</label>
		</td>
	</tr>
    <tr>
        <th><?php _e('Translation Service', 'falang'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="enable_service"  id="enable_service" value="1" <?php echo $falang_model->get_option('enable_service') ? ' checked' : ''; ?>/>
				<?php _e('Enable Translation Service', 'falang'); ?>
            </label>
        </td>
    </tr>
    <tr>
        <th><?php _e('Service name', 'falang'); ?></th>
        <td>
            <select id="service_name" name="service_name" title="Service" class=" required-entry select">
                <option value="yandex" <?php if ( $falang_model->get_option('service_name') == 'yandex' ): ?>selected="selected"<?php endif; ?>><?php _e('Yandex','falang'); ?></option>
                <option value="azure" <?php if ( $falang_model->get_option('service_name') == 'azure' ): ?>selected="selected"<?php endif; ?>><?php echo __('Bing / Azure','falang'); ?></option>
                <option value="lingvanex" <?php if ( $falang_model->get_option('service_name') == 'lingvanex' ): ?>selected="selected"<?php endif; ?>><?php echo __('Lingvanex','falang'); ?></option>
            </select>
	        <?php  \Falang\Core\Falang_Core::falang_tooltip(__('Select Translation Service', 'falang')); ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Yandex API Key', 'falang'); ?></th>
        <td>
            <label>
                <input type="text" size="40" name="yandex_key" value="<?php echo $falang_model->get_option('yandex_key',''); ?>" />
				<?php  \Falang\Core\Falang_Core::falang_tooltip('Sign-up at Yandex for a free access key to the Translator API service at <a href=\'https://tech.yandex.com/translate/\' target="_blank">https://tech.yandex.com/translate/</a>'); ?>
            </label>
        </td>
    </tr>
    <tr>
        <th><?php _e('Microsoft Azure API Key', 'falang'); ?></th>
        <td>
            <label>
                <input type="text" size="40" name="azure_key" value="<?php echo $falang_model->get_option('azure_key',''); ?>" />
                <?php  \Falang\Core\Falang_Core::falang_tooltip('Sign-up at Microsoft Bing Azure for a free access key to the Translator API service at <a href=\'https://www.microsoft.com/en-us/translator/getstarted.aspx\' target="_blank">https://www.microsoft.com/en-us/translator/getstarted.aspx</a>'); ?>
            </label>
        </td>
    </tr>
    <tr>
        <th><?php _e('Lingvanex API Key', 'falang'); ?></th>
        <td>
            <label>
                <input type="text" size="40" name="lingvanex_key" value="<?php echo $falang_model->get_option('lingvanex_key',''); ?>" />
                <?php  \Falang\Core\Falang_Core::falang_tooltip('Sign-up at Lingvanex for a free access key to the Translator API service at <a href=\'https://lingvanex.com/\' target="_blank">https://lingvanex.com/</a>'); ?>
            </label>
        </td>
    </tr>
    <tr>
        <th><?php _e('Debug admin', 'falang'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="debug_admin" value="1" <?php echo $falang_model->get_option('debug_admin') ? ' checked' : ''; ?>/>
			    <?php _e('View debug info in admin section', 'falang'); ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php _e('Delete Translations on uninstall', 'falang'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="delete_trans_on_uninstall" value="1"<?php echo $falang_model->get_option('delete_trans_on_uninstall') ? ' checked' : ''; ?>/>
		        <?php _e('Delete all translations when Falang is uninstalled', 'falang'); ?>
            </label>
        </td>
    </tr>
    </tbody>
</table>
