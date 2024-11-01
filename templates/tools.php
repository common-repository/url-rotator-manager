<script>
   var aName = new Array()
<?php if (count($map) > 0) : ?>
   <?php foreach ($map as $row) : ?>
         aName.push("<?= $row['name']; ?>");
   <?php endforeach; ?>
<?php endif; ?>
</script>

<div class="wrap">
    <h2>URL Rotator Manager</h2>
    <p></p>
    <table class="widefat">
        <thead>
            <tr>
                <th>Inbound</th>
                <th>Outbound</th>
            </tr>
        </thead>
        <tbody>
        <form method="post"> 
            <?php //settings_fields('wp_footer_pop_up_banner'); ?>
            <?php //@do_settings_fields('wp_footer_pop_up_banner'); ?>

            <tr style="background-color: lightgray">

                <td>
                    <label for="url_rotator_manager_name"><?= site_url(); ?>/ </label>
                    <input type="text" class="" name="url_rotator_manager_name" id="url_rotator_manager_name" style="font-size: 80%"/>
                    <input type="submit" id="url_rotator_manager_submit" name="submit"  value="Save" />
                </td>

                <td></td>

            </tr>
            </fieldset>
        </form>

        <?php if (count($map) > 0) : ?>
           <?php foreach ($map as $row) : ?>
              <tr>
                  <td>
                      <?= site_url(); ?>/<?= $row['name']; ?>
                      <form class="url_rotator_manager_delete_form" method="POST" action="tools.php?page=wp_url_rotator_manager" style="display: inline">
                          <input type="hidden" value="<?= $row['name']; ?>" name="url_rotator_manager_delete"/>
                          <input type="submit" value="Delete" />
                      </form>

                      <form class="url_rotator_manager_reset_form" method="POST" action="tools.php?page=wp_url_rotator_manager" style="display: inline">
                          <input type="hidden" value="<?= $row['name']; ?>" name="url_rotator_manager_name"/>
                          <input type="submit" name="url_rotator_manager_reset_submit" value="Reset" />
                      </form>
                  </td>
                  <td>
                      <table class="widefat">
                          <thead>
                              <tr>
                                  <th>URL</th>
                                  <th>Click</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php foreach ($row['link'] as $key => $links) : ?>
                                 <tr>
                                     <td><?= $links['url']; ?></td>
                                     <td><?= $links['click']; ?></td>
                                     <td>
                                         <input type="submit" data-name="<?= $row['name']; ?>" data-key="<?= $key; ?>" data-url="<?= $links['url']; ?>" class="edit" value="Edit" />

                                         <form class="url_rotator_manager_delete_url_form" method="POST" action="tools.php?page=wp_url_rotator_manager" style="display: inline">
                                             <input type="hidden" value="<?= $row['name']; ?>" name="url_rotator_manager_name"/>
                                             <input type="hidden" value="<?= $key; ?>" name="url_rotator_manager_key"/>
                                             <input type="submit" name="url_rotator_manager_delete_url" value="Delete" />
                                         </form>

                                     </td>
                                 </tr>
                              <?php endforeach; ?>

                              <tr style="background-color: lightgray">
                          <form method="post"> 
                              <td>
                                  <label for="url_rotator_manager_name">url: </label>
                                  <input type="text" name="url_rotator_manager_url" id="url_rotator_manager_url_<?= $row['name']; ?>" style="font-size: 80%"/>
                                  <input type="hidden" value="<?= $row['name']; ?>" name="url_rotator_manager_name"/>
                                  <input type="hidden" value="" id="url_rotator_manager_key_<?= $row['name']; ?>"  name="url_rotator_manager_key"/>
                              </td>

                              <td></td>

                              <td>
                                  <input type="submit" id="url_rotator_manager_new_url_submit_<?= $row['name']; ?>" name="url_rotator_manager_new_url_submit"  value="Save" />
                                  <input type="button" class="url_rotator_manager_new_url_cancel" id="url_rotator_manager_new_url_cancel_<?= $row['name']; ?>" data-name="<?= $row['name']; ?>"  value="Cancel" style="display: none"/>
                              </td>
                          </form>
              </tr>
              </tbody>
          </table>
      </td>
      </tr>
   <?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
    
    <a href="http://www.ninjapress.net/suite/" target="_blank">
      <img style="float:right;margin-top: 2em;" src="<?= plugins_url('images/ninjapress-logo.png', dirname(__FILE__)); ?>" />
   </a>
</div>