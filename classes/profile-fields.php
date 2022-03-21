<?php $type_id =   get_the_author_meta( 'aqar_author_type_id' , $user->ID ); ?>
<h2><?php echo esc_html__('Aqar Gate User Id Number && AD Number', 'houzez'); ?></h2>
<table class="form-table">
    <tbody>
        <tr class="user-aqar_author_id_number-wrap">
            <th><label for="aqar_author_id_number"><?php echo esc_html__('Id Number', 'houzez'); ?></label></th>
            <td><input type="text" name="aqar_author_id_number" id="aqar_author_id_number"
                    value="<?php echo get_the_author_meta('aqar_author_id_number', $user->ID); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="aqar_author_ad_number"><?php echo esc_html__('AD Number', 'houzez'); ?></label></th>
            <td><input type="text" name="aqar_author_ad_number" id="aqar_author_ad_number"
                    value="<?php echo get_the_author_meta('aqar_author_ad_number', $user->ID); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th class="form-group">
                <label for="aqar_author_type_id">
                    <?php esc_html_e('نوع المعلن','houzez');?>
                </label>
            </th><!-- form-group -->
            <td>
                <select name="aqar_author_type_id" data-size="5" id="aqar_author_type_id"
                    class="selectpicker form-control regular-text" title="يرجى الاختيار">
                    <option value="" disabled selected>يرجى الاختيار</option>
                    <option <?php echo selected($type_id, '1', false); ?> value="1">مواطن</option>
                    <option <?php echo selected($type_id, '2', false); ?> value="2">مقيم</option>
                    <option <?php echo selected($type_id, '3', false); ?> value="3">منشأة</option>
                </select>
            </td>

        </tr>
    </tbody>
</table>