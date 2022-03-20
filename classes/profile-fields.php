<h2><?php echo esc_html__('Aqar Gate User Id Number', 'houzez'); ?></h2>
<table class="form-table">
    <tbody>
        <tr class="user-aqar_author_id_number-wrap">
            <th><label for="aqar_author_id_number"><?php echo esc_html__('Id Number', 'houzez'); ?></label></th>
            <td><input type="text" name="aqar_author_id_number" id="aqar_author_id_number"
                    value="<?php echo get_the_author_meta('aqar_author_id_number', $user->ID); ?>"
                    class="regular-text"></td>
        </tr>
    </tbody>
</table>